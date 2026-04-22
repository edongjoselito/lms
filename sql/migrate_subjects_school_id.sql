-- Update subjects with NULL school_id to use school_id from their program or grade level
UPDATE subjects s
LEFT JOIN programs p ON s.program_id = p.id
LEFT JOIN grade_levels gl ON s.grade_level_id = gl.id
SET s.school_id = COALESCE(p.school_id, gl.school_id, 1)
WHERE s.school_id IS NULL OR s.school_id = 0;
