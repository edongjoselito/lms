<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Quiz_model extends CI_Model {

    // ---- Quizzes ----
    public function get_quizzes($class_program_id)
    {
        return $this->db->where('class_program_id', $class_program_id)
                        ->order_by('created_at', 'DESC')
                        ->get('quizzes')
                        ->result();
    }

    public function get_quizzes_by_course($course_id)
    {
        return $this->db->where('course_id', $course_id)
                        ->order_by('created_at', 'DESC')
                        ->get('quizzes')
                        ->result();
    }

    public function get_quiz($id)
    {
        return $this->db->where('id', $id)->get('quizzes')->row();
    }

    public function create_quiz($data)
    {
        $this->db->insert('quizzes', $data);
        return $this->db->insert_id();
    }

    public function update_quiz($id, $data)
    {
        return $this->db->where('id', $id)->update('quizzes', $data);
    }

    public function delete_quiz($id)
    {
        // Cascade: answers → attempts, choices → questions → quiz
        $questions = $this->get_questions($id);
        foreach ($questions as $q) {
            $this->db->where('question_id', $q->id)->delete('quiz_choices');
        }
        $attempts = $this->db->where('quiz_id', $id)->get('quiz_attempts')->result();
        foreach ($attempts as $a) {
            $this->db->where('attempt_id', $a->id)->delete('quiz_attempt_answers');
        }
        $this->db->where('quiz_id', $id)->delete('quiz_attempts');
        $this->db->where('quiz_id', $id)->delete('quiz_questions');
        return $this->db->where('id', $id)->delete('quizzes');
    }

    public function count_quizzes($class_program_id)
    {
        return $this->db->where('class_program_id', $class_program_id)
                        ->count_all_results('quizzes');
    }

    // ---- Questions ----
    public function get_questions($quiz_id)
    {
        return $this->db->where('quiz_id', $quiz_id)
                        ->order_by('order_num', 'ASC')
                        ->get('quiz_questions')
                        ->result();
    }

    public function get_question($id)
    {
        return $this->db->where('id', $id)->get('quiz_questions')->row();
    }

    public function create_question($data)
    {
        $this->db->insert('quiz_questions', $data);
        return $this->db->insert_id();
    }

    public function update_question($id, $data)
    {
        return $this->db->where('id', $id)->update('quiz_questions', $data);
    }

    public function delete_question($id)
    {
        $this->db->where('question_id', $id)->delete('quiz_choices');
        return $this->db->where('id', $id)->delete('quiz_questions');
    }

    public function count_questions($quiz_id)
    {
        return $this->db->where('quiz_id', $quiz_id)->count_all_results('quiz_questions');
    }

    public function get_next_question_order($quiz_id)
    {
        $row = $this->db->select_max('order_num')->where('quiz_id', $quiz_id)->get('quiz_questions')->row();
        return ($row && $row->order_num) ? $row->order_num + 1 : 1;
    }

    // ---- Choices ----
    public function get_choices($question_id)
    {
        return $this->db->where('question_id', $question_id)
                        ->order_by('order_num', 'ASC')
                        ->get('quiz_choices')
                        ->result();
    }

    public function save_choices($question_id, $choices)
    {
        $this->db->where('question_id', $question_id)->delete('quiz_choices');
        foreach ($choices as $i => $choice) {
            $this->db->insert('quiz_choices', array(
                'question_id' => $question_id,
                'choice_text' => $choice['text'],
                'is_correct'  => !empty($choice['is_correct']) ? 1 : 0,
                'order_num'   => $i + 1,
            ));
        }
    }

    // ---- Attempts ----
    public function start_attempt($quiz_id, $student_id)
    {
        $attempt_num = $this->db->where('quiz_id', $quiz_id)
                                ->where('student_id', $student_id)
                                ->count_all_results('quiz_attempts') + 1;
        $data = array(
            'quiz_id'        => $quiz_id,
            'student_id'     => $student_id,
            'attempt_number' => $attempt_num,
            'status'         => 'in_progress',
        );
        $this->db->insert('quiz_attempts', $data);
        return $this->db->insert_id();
    }

    public function get_attempt($id)
    {
        return $this->db->where('id', $id)->get('quiz_attempts')->row();
    }

    public function get_student_attempts($quiz_id, $student_id)
    {
        return $this->db->where('quiz_id', $quiz_id)
                        ->where('student_id', $student_id)
                        ->order_by('attempt_number', 'ASC')
                        ->get('quiz_attempts')
                        ->result();
    }

    public function get_all_attempts($quiz_id)
    {
        return $this->db->select('quiz_attempts.*, CONCAT(u.first_name, " ", u.last_name) as student_name, u.email', FALSE)
                        ->join('users u', 'u.id = quiz_attempts.student_id')
                        ->where('quiz_attempts.quiz_id', $quiz_id)
                        ->order_by('u.last_name', 'ASC')
                        ->order_by('quiz_attempts.attempt_number', 'ASC')
                        ->get('quiz_attempts')
                        ->result();
    }

    public function get_in_progress_attempt($quiz_id, $student_id)
    {
        return $this->db->where('quiz_id', $quiz_id)
                        ->where('student_id', $student_id)
                        ->where('status', 'in_progress')
                        ->get('quiz_attempts')
                        ->row();
    }

    public function save_answer($attempt_id, $question_id, $data)
    {
        $existing = $this->db->where('attempt_id', $attempt_id)
                             ->where('question_id', $question_id)
                             ->get('quiz_attempt_answers')
                             ->row();
        if ($existing) {
            return $this->db->where('id', $existing->id)->update('quiz_attempt_answers', $data);
        } else {
            $data['attempt_id'] = $attempt_id;
            $data['question_id'] = $question_id;
            $this->db->insert('quiz_attempt_answers', $data);
            return $this->db->insert_id();
        }
    }

    public function get_attempt_answers($attempt_id)
    {
        return $this->db->where('attempt_id', $attempt_id)
                        ->get('quiz_attempt_answers')
                        ->result();
    }

    public function submit_attempt($attempt_id)
    {
        $attempt = $this->get_attempt($attempt_id);
        if (!$attempt) return false;

        $quiz = $this->get_quiz($attempt->quiz_id);
        $questions = $this->get_questions($attempt->quiz_id);
        $answers = $this->get_attempt_answers($attempt_id);
        $answer_map = array();
        foreach ($answers as $a) {
            $answer_map[$a->question_id] = $a;
        }

        $total_score = 0;
        $total_points = 0;

        foreach ($questions as $q) {
            $total_points += $q->points;

            if ($q->question_type === 'essay') continue; // manually graded

            $ans = isset($answer_map[$q->id]) ? $answer_map[$q->id] : null;
            if (!$ans) continue;

            $is_correct = 0;
            $score = 0;

            if ($q->question_type === 'multiple_choice' || $q->question_type === 'true_false') {
                if ($ans->choice_id) {
                    $choice = $this->db->where('id', $ans->choice_id)->get('quiz_choices')->row();
                    if ($choice && $choice->is_correct) {
                        $is_correct = 1;
                        $score = $q->points;
                    }
                }
            } elseif ($q->question_type === 'identification') {
                $correct_choices = $this->db->where('question_id', $q->id)
                                           ->where('is_correct', 1)
                                           ->get('quiz_choices')
                                           ->result();
                foreach ($correct_choices as $cc) {
                    if (strtolower(trim($ans->answer_text)) === strtolower(trim($cc->choice_text))) {
                        $is_correct = 1;
                        $score = $q->points;
                        break;
                    }
                }
            }

            $this->db->where('id', $ans->id)->update('quiz_attempt_answers', array(
                'is_correct' => $is_correct,
                'score'      => $score,
            ));
            $total_score += $score;
        }

        $has_essay = false;
        foreach ($questions as $q) {
            if ($q->question_type === 'essay') { $has_essay = true; break; }
        }

        $update = array(
            'score'        => $total_score,
            'total_points' => $total_points,
            'percentage'   => $total_points > 0 ? round(($total_score / $total_points) * 100, 2) : 0,
            'status'       => $has_essay ? 'submitted' : 'graded',
            'submitted_at' => date('Y-m-d H:i:s'),
        );
        if (!$has_essay) {
            $update['graded_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->where('id', $attempt_id)->update('quiz_attempts', $update);
    }

    public function grade_essay($answer_id, $score, $feedback, $graded_by)
    {
        $this->db->where('id', $answer_id)->update('quiz_attempt_answers', array(
            'score'    => $score,
            'feedback' => $feedback,
        ));

        $answer = $this->db->where('id', $answer_id)->get('quiz_attempt_answers')->row();
        $attempt = $this->get_attempt($answer->attempt_id);

        // Recalculate total
        $total = $this->db->select_sum('score')
                          ->where('attempt_id', $attempt->id)
                          ->get('quiz_attempt_answers')
                          ->row();
        $new_score = $total->score ?: 0;

        $all_graded = $this->db->where('attempt_id', $attempt->id)
                               ->where('score IS NULL')
                               ->count_all_results('quiz_attempt_answers') == 0;

        $update = array('score' => $new_score);
        if ($all_graded) {
            $update['status'] = 'graded';
            $update['graded_at'] = date('Y-m-d H:i:s');
            $update['graded_by'] = $graded_by;
            $update['percentage'] = $attempt->total_points > 0 ? round(($new_score / $attempt->total_points) * 100, 2) : 0;
        }

        return $this->db->where('id', $attempt->id)->update('quiz_attempts', $update);
    }
}
