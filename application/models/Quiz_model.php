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

    public function get_quiz_by_activity($activity_id)
    {
        return $this->db->where('component_id', $activity_id)
                        ->get('quizzes')
                        ->row();
    }

    public function get_quiz($id)
    {
        return $this->db->where('id', $id)->get('quizzes')->row();
    }

    public function get_subject_quiz_activity_ids($subject_id, $published_only = true)
    {
        $this->db->select('DISTINCT(a.id) as activity_id', FALSE)
                 ->from('activities a')
                 ->join('modules m', 'm.id = a.module_id')
                 ->join('quizzes q', 'q.component_id = a.id')
                 ->where('m.subject_id', $subject_id)
                 ->where('a.type', 'quiz');

        if ($published_only) {
            $this->db->where('m.is_published', 1)
                     ->where('a.is_published', 1)
                     ->where('q.is_published', 1);
        }

        $rows = $this->db->get()->result();
        return array_map(function($row) { return (int) $row->activity_id; }, $rows);
    }

    public function get_completed_quiz_activity_ids_by_subject($subject_id, $student_id, $published_only = true)
    {
        $this->db->select('DISTINCT(a.id) as activity_id', FALSE)
                 ->from('quiz_attempts qa')
                 ->join('quizzes q', 'q.id = qa.quiz_id')
                 ->join('activities a', 'a.id = q.component_id')
                 ->join('modules m', 'm.id = a.module_id')
                 ->where('qa.student_id', $student_id)
                 ->where_in('qa.status', array('submitted', 'graded'))
                 ->where('m.subject_id', $subject_id)
                 ->where('a.type', 'quiz');

        if ($published_only) {
            $this->db->where('m.is_published', 1)
                     ->where('a.is_published', 1)
                     ->where('q.is_published', 1);
        }

        $rows = $this->db->get()->result();
        return array_map(function($row) { return (int) $row->activity_id; }, $rows);
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

    public function get_total_points($quiz_id)
    {
        $row = $this->db->select_sum('points')
                        ->where('quiz_id', $quiz_id)
                        ->get('quiz_questions')
                        ->row();
        return $row && $row->points ? (float) $row->points : 0;
    }

    public function recalculate_total_points($quiz_id)
    {
        $total_points = $this->get_total_points($quiz_id);
        $this->update_quiz($quiz_id, array('total_points' => $total_points));
        return $total_points;
    }

    public function get_questions_with_choices($quiz_id)
    {
        $questions = $this->get_questions($quiz_id);
        foreach ($questions as &$question) {
            $question->choices = $this->get_choices($question->id);
        }
        unset($question);
        return $questions;
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
            'started_at'     => date('Y-m-d H:i:s'),
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

    public function count_student_attempts($quiz_id, $student_id)
    {
        return $this->db->where('quiz_id', $quiz_id)
                        ->where('student_id', $student_id)
                        ->where_in('status', array('submitted', 'graded'))
                        ->count_all_results('quiz_attempts');
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

    public function get_quiz_analysis($quiz_id)
    {
        $attempts = $this->db->select('quiz_attempts.*')
                        ->where('quiz_attempts.quiz_id', $quiz_id)
                        ->where_in('quiz_attempts.status', array('submitted', 'graded'))
                        ->get('quiz_attempts')
                        ->result();

        $analysis = array(
            'total_attempts' => count($attempts),
            'unique_students' => 0,
            'average_score' => 0,
            'highest_score' => 0,
            'lowest_score' => 0,
            'pass_count' => 0,
            'fail_count' => 0,
            'pass_rate' => 0,
            'question_analysis' => array(),
            'thematic_analysis' => array()
        );

        if (empty($attempts)) {
            return (object) $analysis;
        }

        $student_ids = array();
        $scores = array();
        $total_points = 0;

        foreach ($attempts as $attempt) {
            if (!in_array($attempt->student_id, $student_ids)) {
                $student_ids[] = $attempt->student_id;
            }
            if ($attempt->score !== null) {
                $scores[] = (float) $attempt->score;
                $total_points = (float) $attempt->total_points;
                if ($attempt->score >= ($attempt->total_points * 0.6)) {
                    $analysis['pass_count']++;
                } else {
                    $analysis['fail_count']++;
                }
            }
        }

        $analysis['unique_students'] = count($student_ids);

        if (!empty($scores)) {
            $analysis['average_score'] = array_sum($scores) / count($scores);
            $analysis['highest_score'] = max($scores);
            $analysis['lowest_score'] = min($scores);
            $analysis['pass_rate'] = ($analysis['pass_count'] / count($scores)) * 100;
        }

        // Question analysis - get correct answer rates per question
        $questions = $this->db->select('id, question_text, question_type, points')
                             ->where('quiz_id', $quiz_id)
                             ->get('quiz_questions')
                             ->result();

        $theme_data = array();

        foreach ($questions as $question) {
            $total_answers = $this->db->select('COUNT(*) as count')
                                     ->from('quiz_attempt_answers qaa')
                                     ->join('quiz_attempts qa', 'qa.id = qaa.attempt_id')
                                     ->where('qaa.question_id', $question->id)
                                     ->where_in('qa.status', array('submitted', 'graded'))
                                     ->get()
                                     ->row()->count;

            $correct_answers = $this->db->select('COUNT(*) as count')
                                       ->from('quiz_attempt_answers qaa')
                                       ->join('quiz_attempts qa', 'qa.id = qaa.attempt_id')
                                       ->where('qaa.question_id', $question->id)
                                       ->where('qaa.is_correct', 1)
                                       ->where_in('qa.status', array('submitted', 'graded'))
                                       ->get()
                                       ->row()->count;

            $correct_rate = $total_answers > 0 ? ($correct_answers / $total_answers) * 100 : 0;

            $analysis['question_analysis'][] = (object) array(
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'points' => $question->points,
                'total_answers' => $total_answers,
                'correct_answers' => $correct_answers,
                'correct_rate' => $correct_rate
            );

            // Thematic analysis by question type
            $theme = $question->question_type;
            if (!isset($theme_data[$theme])) {
                $theme_data[$theme] = array(
                    'theme' => $theme,
                    'theme_label' => str_replace('_', ' ', ucfirst($theme)),
                    'total_questions' => 0,
                    'total_points' => 0,
                    'total_answers' => 0,
                    'correct_answers' => 0
                );
            }
            $theme_data[$theme]['total_questions']++;
            $theme_data[$theme]['total_points'] += (float) $question->points;
            $theme_data[$theme]['total_answers'] += $total_answers;
            $theme_data[$theme]['correct_answers'] += $correct_answers;
        }

        // Calculate theme statistics
        foreach ($theme_data as $theme) {
            $theme['correct_rate'] = $theme['total_answers'] > 0 ? ($theme['correct_answers'] / $theme['total_answers']) * 100 : 0;
            $analysis['thematic_analysis'][] = (object) $theme;
        }

        return (object) $analysis;
    }

    public function generate_analysis_description($analysis, $lang = 'en')
    {
        if ($analysis->total_attempts === 0) {
            if ($lang === 'tl') {
                return "Wala pang naitatalang mga pagsubok ng mga mag-aaral. Magkakaroon ng analysis kapag nakapagtapos na ang mga mag-aaral sa pagsusulit.";
            }
            return "No student attempts have been recorded yet. Analysis will be available once students have completed the assessment.";
        }

        if ($lang === 'tl') {
            return $this->generate_tagalog_description($analysis);
        }

        $description = "This assessment has been attempted " . $analysis->total_attempts . " time";
        if ($analysis->total_attempts > 1) {
            $description .= "s";
        }
        $description .= " by " . $analysis->unique_students . " unique student";
        if ($analysis->unique_students > 1) {
            $description .= "s";
        }
        $description .= ". ";

        if ($analysis->average_score > 0) {
            $description .= "The average score is " . number_format((float) $analysis->average_score, 2) . " points, with a highest score of " . number_format((float) $analysis->highest_score, 2) . " and a lowest score of " . number_format((float) $analysis->lowest_score, 2) . ". ";
        }

        $description .= "The pass rate is " . number_format((float) $analysis->pass_rate, 1) . "%";
        if ($analysis->pass_rate >= 80) {
            $description .= ", indicating strong overall performance across the class.";
        } elseif ($analysis->pass_rate >= 60) {
            $description .= ", showing satisfactory performance with room for improvement.";
        } elseif ($analysis->pass_rate >= 40) {
            $description .= ", suggesting that many students struggled with the assessment content.";
        } else {
            $description .= ", indicating significant difficulty with the assessment material.";
        }
        $description .= " ";

        if (!empty($analysis->thematic_analysis)) {
            $description .= "By question type, ";
            $theme_descriptions = array();
            foreach ($analysis->thematic_analysis as $theme) {
                $theme_desc = $theme->theme_label . " questions had a " . number_format((float) $theme->correct_rate, 1) . "% correct rate";
                if ($theme->correct_rate >= 70) {
                    $theme_desc .= " (well-performed)";
                } elseif ($theme->correct_rate >= 50) {
                    $theme_desc .= " (moderate performance)";
                } else {
                    $theme_desc .= " (needs attention)";
                }
                $theme_descriptions[] = $theme_desc;
            }
            $description .= implode(", ", $theme_descriptions) . ". ";
        }

        if (!empty($analysis->question_analysis)) {
            $difficult_questions = array_filter($analysis->question_analysis, function($qa) {
                return $qa->correct_rate < 50 && $qa->total_answers > 0;
            });
            if (count($difficult_questions) > 0) {
                $description .= "Several questions had correct rates below 50%, which may indicate areas where students need additional review or instructional support. ";
            }

            $easy_questions = array_filter($analysis->question_analysis, function($qa) {
                return $qa->correct_rate >= 80 && $qa->total_answers > 0;
            });
            if (count($easy_questions) > 0) {
                $description .= "Conversely, some questions were well-understood by students with correct rates above 80%. ";
            }
        }

        return $description;
    }

    private function generate_tagalog_description($analysis)
    {
        $description = "Ang pagsusulit na ito ay sinubok " . $analysis->total_attempts . " na bes";
        if ($analysis->total_attempts > 1) {
            $description .= "es";
        }
        $description .= " ng " . $analysis->unique_students . " na mag-aaral. ";

        if ($analysis->average_score > 0) {
            $description .= "Ang average na score ay " . number_format((float) $analysis->average_score, 2) . " puntos, na may pinakamataas na score na " . number_format((float) $analysis->highest_score, 2) . " at pinakamababang score na " . number_format((float) $analysis->lowest_score, 2) . ". ";
        }

        $description .= "Ang pass rate ay " . number_format((float) $analysis->pass_rate, 1) . "%";
        if ($analysis->pass_rate >= 80) {
            $description .= ", na nagpapakita ng magandang pagganap ng buong klase.";
        } elseif ($analysis->pass_rate >= 60) {
            $description .= ", na nagpapakita ng katamtamang pagganap na may puwang sa pagpapabuti.";
        } elseif ($analysis->pass_rate >= 40) {
            $description .= ", na nagpapahiwatig na maraming mag-aaral ang nahirapan sa nilalaman ng pagsusulit.";
        } else {
            $description .= ", na nagpapakita ng malaking hirap sa materyal ng pagsusulit.";
        }
        $description .= " ";

        if (!empty($analysis->thematic_analysis)) {
            $description .= "Ayon sa uri ng tanong, ";
            $theme_descriptions = array();
            foreach ($analysis->thematic_analysis as $theme) {
                $theme_desc = "ang mga tanong na " . $theme->theme_label . " ay may " . number_format((float) $theme->correct_rate, 1) . "% na tamang sagot";
                if ($theme->correct_rate >= 70) {
                    $theme_desc .= " (mabuting pagganap)";
                } elseif ($theme->correct_rate >= 50) {
                    $theme_desc .= " (katamtamang pagganap)";
                } else {
                    $theme_desc .= " (kailangan ng pansin)";
                }
                $theme_descriptions[] = $theme_desc;
            }
            $description .= implode(", ", $theme_descriptions) . ". ";
        }

        if (!empty($analysis->question_analysis)) {
            $difficult_questions = array_filter($analysis->question_analysis, function($qa) {
                return $qa->correct_rate < 50 && $qa->total_answers > 0;
            });
            if (count($difficult_questions) > 0) {
                $description .= "Ilang mga tanong ang may tamang sagot na mas mababa sa 50%, na maaaring magpapakita ng mga lugar kung saan kailangan ng mga mag-aaral ng karagdagang pagsusuri o suportang pangturo. ";
            }

            $easy_questions = array_filter($analysis->question_analysis, function($qa) {
                return $qa->correct_rate >= 80 && $qa->total_answers > 0;
            });
            if (count($easy_questions) > 0) {
                $description .= "Sa kabilang banda, ilang mga tanong ang naintindihan nang mabuti ng mga mag-aaral na may tamang sagot na higit sa 80%. ";
            }
        }

        return $description;
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

    public function get_attempt_answers_map($attempt_id)
    {
        $answers = $this->get_attempt_answers($attempt_id);
        $answer_map = array();
        foreach ($answers as $answer) {
            $answer_map[(int) $answer->question_id] = $answer;
        }
        return $answer_map;
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
