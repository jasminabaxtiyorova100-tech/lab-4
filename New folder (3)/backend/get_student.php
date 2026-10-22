
<?php

// Sends all saved students to the frontend as JSON

require_once "config.php";

// Load all students
$students = load_students();

// Calculate the grade for each student
foreach ($students as &$student) {
    $student["grade"] = grade($student["marks"]);
}

unset($student);

// Calculate the class average
$average = count($students) > 0
    ? round(
        array_sum(array_column($students, "marks")) / count($students),
        1
    )
    : 0;

// Return the data as JSON
echo json_encode([
    "count" => count($students),
    "average" => $average,
    "students" => $students
], JSON_UNESCAPED_UNICODE);

?>