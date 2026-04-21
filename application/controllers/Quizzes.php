<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quizzes extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(array('Quiz_model', 'Course_model'));
    }

    // ---- Quiz CRUD (course-based) ----
    public function create($course_id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'course_id'          => $course_id,
                'school_id'          => $this->school_id,
                'title'              => $this->input->post('title', TRUE),
                'description'        => $this->input->post('description', TRUE),
                'quiz_type'          => $this->input->post('quiz_type', TRUE),
                'total_points'       => $this->input->post('total_points') ?: NULL,
                'time_limit_minutes' => $this->input->post('time_limit_minutes') ?: NULL,
                'max_attempts'       => $this->input->post('max_attempts') ?: 1,
                'shuffle_questions'  => $this->input->post('shuffle_questions') ? 1 : 0,
                'show_results'       => $this->input->post('show_results') ? 1 : 0,
                'available_from'     => $this->input->post('available_from') ?: NULL,
                'available_until'    => $this->input->post('available_until') ?: NULL,
                'is_published'       => $this->input->post('is_published') ? 1 : 0,
                'created_by'         => $this->current_user->id,
            );
            $quiz_id = $this->Quiz_model->create_quiz($d);
            $this->session->set_flashdata('success', 'Assessment created. Now add questions.');
            redirect('quizzes/questions/' . $quiz_id);
        }

        $data['title'] = 'Create Assessment';
        $data['course'] = $course;
        $data['quiz'] = null;
        $this->render('quizzes/form', $data);
    }

    public function edit($id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $quiz = $this->Quiz_model->get_quiz($id);
        if (!$quiz) show_404();
        $course = $this->Course_model->get_course($quiz->course_id);

        // Check if quiz has attempts
        $attempt_count = $this->db->where('quiz_id', $id)->count_all_results('quiz_attempts');
        if ($attempt_count > 0) {
            $this->session->set_flashdata('error', 'Cannot edit assessment. ' . $attempt_count . ' student(s) have already taken this quiz.');
            redirect('courses/view/' . $quiz->course_id);
            return;
        }

        if ($this->input->method() === 'post') {
            $d = array(
                'title'              => $this->input->post('title', TRUE),
                'description'        => $this->input->post('description', TRUE),
                'quiz_type'          => $this->input->post('quiz_type', TRUE),
                'total_points'       => $this->input->post('total_points') ?: NULL,
                'time_limit_minutes' => $this->input->post('time_limit_minutes') ?: NULL,
                'max_attempts'       => $this->input->post('max_attempts') ?: 1,
                'shuffle_questions'  => $this->input->post('shuffle_questions') ? 1 : 0,
                'show_results'       => $this->input->post('show_results') ? 1 : 0,
                'available_from'     => $this->input->post('available_from') ?: NULL,
                'available_until'    => $this->input->post('available_until') ?: NULL,
                'is_published'       => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Quiz_model->update_quiz($id, $d);
            $this->session->set_flashdata('success', 'Assessment updated.');
            redirect('courses/view/' . $quiz->course_id);
        }

        $data['title'] = 'Edit Assessment';
        $data['course'] = $course;
        $data['quiz'] = $quiz;
        $this->render('quizzes/form', $data);
    }

    public function delete($id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $quiz = $this->Quiz_model->get_quiz($id);
        if (!$quiz) show_404();
        $course_id = $quiz->course_id;

        // Check if quiz has attempts
        $attempt_count = $this->db->where('quiz_id', $id)->count_all_results('quiz_attempts');
        if ($attempt_count > 0) {
            $this->session->set_flashdata('error', 'Cannot delete assessment. ' . $attempt_count . ' student(s) have already taken this quiz.');
            redirect('courses/view/' . $course_id);
            return;
        }

        $this->Quiz_model->delete_quiz($id);
        $this->session->set_flashdata('success', 'Assessment deleted.');
        redirect('courses/view/' . $course_id);
    }

    // ---- Question Builder ----
    public function questions($quiz_id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $quiz = $this->Quiz_model->get_quiz($quiz_id);
        if (!$quiz) show_404();

        // Check if quiz has attempts
        $attempt_count = $this->db->where('quiz_id', $quiz_id)->count_all_results('quiz_attempts');
        $has_attempts = $attempt_count > 0;

        $questions = $this->Quiz_model->get_questions($quiz_id);
        foreach ($questions as &$q) {
            $q->choices = $this->Quiz_model->get_choices($q->id);
        }

        $course = $this->Course_model->get_course($quiz->course_id);
        $data['title'] = 'Questions: ' . $quiz->title;
        $data['quiz'] = $quiz;
        $data['course'] = $course;
        $data['questions'] = $questions;
        $data['has_attempts'] = $has_attempts;
        $data['attempt_count'] = $attempt_count;
        $this->render('quizzes/questions', $data);
    }

    public function add_question($quiz_id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $quiz = $this->Quiz_model->get_quiz($quiz_id);
        if (!$quiz) show_404();

        // Check if quiz has attempts
        $attempt_count = $this->db->where('quiz_id', $quiz_id)->count_all_results('quiz_attempts');
        if ($attempt_count > 0) {
            $this->session->set_flashdata('error', 'Cannot add questions. ' . $attempt_count . ' student(s) have already taken this quiz.');
            redirect('quizzes/questions/' . $quiz_id);
            return;
        }

        if ($this->input->method() === 'post') {
            $qdata = array(
                'quiz_id'       => $quiz_id,
                'question_type' => $this->input->post('question_type', TRUE),
                'question_text' => $this->input->post('question_text'),
                'points'        => $this->input->post('points') ?: 1,
                'order_num'     => $this->Quiz_model->get_next_question_order($quiz_id),
            );
            $q_id = $this->Quiz_model->create_question($qdata);

            $choice_texts = $this->input->post('choice_text');
            $correct = $this->input->post('correct_choice');
            if ($choice_texts && is_array($choice_texts)) {
                $choices = array();
                foreach ($choice_texts as $i => $text) {
                    if (trim($text) === '') continue;
                    $choices[] = array(
                        'text'       => $text,
                        'is_correct' => ($qdata['question_type'] === 'identification') ? 1 : ($correct == $i ? 1 : 0),
                    );
                }
                $this->Quiz_model->save_choices($q_id, $choices);
            }

            $this->_recalc_total_points($quiz_id);
            $this->session->set_flashdata('success', 'Question added.');
            redirect('quizzes/questions/' . $quiz_id);
        }

        $course = $this->Course_model->get_course($quiz->course_id);
        $data['title'] = 'Add Question';
        $data['quiz'] = $quiz;
        $data['course'] = $course;
        $data['question'] = null;
        $this->render('quizzes/question_form', $data);
    }

    public function edit_question($id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $question = $this->Quiz_model->get_question($id);
        if (!$question) show_404();
        $quiz = $this->Quiz_model->get_quiz($question->quiz_id);

        // Check if quiz has attempts
        $attempt_count = $this->db->where('quiz_id', $question->quiz_id)->count_all_results('quiz_attempts');
        if ($attempt_count > 0) {
            $this->session->set_flashdata('error', 'Cannot edit question. ' . $attempt_count . ' student(s) have already taken this quiz.');
            redirect('quizzes/questions/' . $question->quiz_id);
            return;
        }

        if ($this->input->method() === 'post') {
            $qdata = array(
                'question_type' => $this->input->post('question_type', TRUE),
                'question_text' => $this->input->post('question_text'),
                'points'        => $this->input->post('points') ?: 1,
            );
            $this->Quiz_model->update_question($id, $qdata);

            $choice_texts = $this->input->post('choice_text');
            $correct = $this->input->post('correct_choice');
            if ($choice_texts && is_array($choice_texts)) {
                $choices = array();
                foreach ($choice_texts as $i => $text) {
                    if (trim($text) === '') continue;
                    $choices[] = array(
                        'text'       => $text,
                        'is_correct' => ($qdata['question_type'] === 'identification') ? 1 : ($correct == $i ? 1 : 0),
                    );
                }
                $this->Quiz_model->save_choices($id, $choices);
            }

            $this->_recalc_total_points($question->quiz_id);
            $this->session->set_flashdata('success', 'Question updated.');
            redirect('quizzes/questions/' . $question->quiz_id);
        }

        $course = $this->Course_model->get_course($quiz->course_id);
        $data['title'] = 'Edit Question';
        $data['quiz'] = $quiz;
        $data['course'] = $course;
        $data['question'] = $question;
        $data['choices'] = $this->Quiz_model->get_choices($id);
        $this->render('quizzes/question_form', $data);
    }

    public function delete_question($id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $question = $this->Quiz_model->get_question($id);
        if (!$question) show_404();
        $quiz_id = $question->quiz_id;

        // Check if quiz has attempts
        $attempt_count = $this->db->where('quiz_id', $quiz_id)->count_all_results('quiz_attempts');
        if ($attempt_count > 0) {
            $this->session->set_flashdata('error', 'Cannot delete question. ' . $attempt_count . ' student(s) have already taken this quiz.');
            redirect('quizzes/questions/' . $quiz_id);
            return;
        }

        $this->Quiz_model->delete_question($id);
        $this->_recalc_total_points($quiz_id);
        $this->session->set_flashdata('success', 'Question deleted.');
        redirect('quizzes/questions/' . $quiz_id);
    }

    // ---- Student: Take Quiz ----
    public function take($quiz_id)
    {
        $this->require_role(array('student'));
        $quiz = $this->Quiz_model->get_quiz($quiz_id);
        if (!$quiz || !$quiz->is_published) show_404();

        $user_id = $this->current_user->id;

        // Check attempts
        $attempts = $this->Quiz_model->get_student_attempts($quiz_id, $user_id);
        $in_progress = $this->Quiz_model->get_in_progress_attempt($quiz_id, $user_id);

        if ($in_progress) {
            $attempt = $in_progress;
        } elseif (count($attempts) >= $quiz->max_attempts) {
            // Redirect to last attempt result instead of showing error
            $last_attempt = end($attempts);
            $this->session->set_flashdata('info', 'Maximum attempts reached. Viewing your last submission.');
            redirect('quizzes/result/' . $last_attempt->id);
            return;
        } else {
            $attempt_id = $this->Quiz_model->start_attempt($quiz_id, $user_id);
            $attempt = $this->Quiz_model->get_attempt($attempt_id);
        }

        $questions = $this->Quiz_model->get_questions($quiz_id);
        if ($quiz->shuffle_questions) shuffle($questions);

        foreach ($questions as &$q) {
            $q->choices = $this->Quiz_model->get_choices($q->id);
        }

        $answers = $this->Quiz_model->get_attempt_answers($attempt->id);
        $answer_map = array();
        foreach ($answers as $a) {
            $answer_map[$a->question_id] = $a;
        }

        $data['title'] = $quiz->title;
        $data['quiz'] = $quiz;
        $data['attempt'] = $attempt;
        $data['questions'] = $questions;
        $data['answer_map'] = $answer_map;
        $this->render('quizzes/take', $data);
    }

    public function submit($attempt_id)
    {
        $this->require_role(array('student'));
        $attempt = $this->Quiz_model->get_attempt($attempt_id);
        if (!$attempt || $attempt->status !== 'in_progress') show_404();

        $user_id = $this->current_user->id;
        if ($attempt->student_id != $user_id) show_error('Unauthorized', 403);

        $questions = $this->Quiz_model->get_questions($attempt->quiz_id);

        foreach ($questions as $q) {
            $answer_data = array();
            if ($q->question_type === 'multiple_choice' || $q->question_type === 'true_false') {
                $answer_data['choice_id'] = $this->input->post('answer_' . $q->id) ?: NULL;
            } else {
                $answer_data['answer_text'] = $this->input->post('answer_' . $q->id);
            }
            $this->Quiz_model->save_answer($attempt->id, $q->id, $answer_data);
        }

        $this->Quiz_model->submit_attempt($attempt->id);
        $this->session->set_flashdata('success', 'Assessment submitted successfully.');
        redirect('quizzes/result/' . $attempt->id);
    }

    public function result($attempt_id)
    {
        $attempt = $this->Quiz_model->get_attempt($attempt_id);
        if (!$attempt) show_404();

        $quiz = $this->Quiz_model->get_quiz($attempt->quiz_id);

        if ($this->is_student() && $attempt->student_id != $this->current_user->id) {
            show_error('Unauthorized', 403);
        }

        $questions = $this->Quiz_model->get_questions($attempt->quiz_id);
        $answers = $this->Quiz_model->get_attempt_answers($attempt->id);
        $answer_map = array();
        foreach ($answers as $a) {
            $answer_map[$a->question_id] = $a;
        }

        foreach ($questions as &$q) {
            $q->choices = $this->Quiz_model->get_choices($q->id);
            $q->student_answer = isset($answer_map[$q->id]) ? $answer_map[$q->id] : null;
        }

        $course = $this->Course_model->get_course($quiz->course_id);
        $data['title'] = 'Result: ' . $quiz->title;
        $data['quiz'] = $quiz;
        $data['attempt'] = $attempt;
        $data['questions'] = $questions;
        $data['course'] = $course;
        $this->render('quizzes/result', $data);
    }

    public function attempts($quiz_id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $quiz = $this->Quiz_model->get_quiz($quiz_id);
        if (!$quiz) show_404();

        $course = $this->Course_model->get_course($quiz->course_id);
        $data['title'] = 'Submissions: ' . $quiz->title;
        $data['quiz'] = $quiz;
        $data['course'] = $course;
        $data['attempts'] = $this->Quiz_model->get_all_attempts($quiz_id);
        $this->render('quizzes/attempts', $data);
    }

    public function grade_essay($answer_id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $score = $this->input->post('score');
        $feedback = $this->input->post('feedback');

        $answer = $this->db->where('id', $answer_id)->get('quiz_attempt_answers')->row();
        if (!$answer) show_404();

        $this->Quiz_model->grade_essay($answer_id, $score, $feedback, $this->current_user->id);

        $this->session->set_flashdata('success', 'Essay graded successfully.');
        redirect('quizzes/result/' . $answer->attempt_id);
    }

    private function _recalc_total_points($quiz_id)
    {
        $total = $this->db->select_sum('points')->where('quiz_id', $quiz_id)->get('quiz_questions')->row();
        $this->Quiz_model->update_quiz($quiz_id, array('total_points' => $total->points ?: 0));
    }
}
