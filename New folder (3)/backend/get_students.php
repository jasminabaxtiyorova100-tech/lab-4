<?php

// Sends all saved students to the frontend as JSON

require_once "config.php";

// Load all students
$students = load_students();

// Calculate grades
foreach ($students as &$student) {
    $student["grade"] = grade($student["marks"]);
}

unset($student);

// Calculate class average
$average = count($students)
    ? round(
        array_sum(array_column($students, "marks")) / count($students),
        1
    )
    : 0;

// Return data as JSON
echo json_encode([
    "count" => count($students),
    "average" => $average,
    "students" => $students
]);

?>