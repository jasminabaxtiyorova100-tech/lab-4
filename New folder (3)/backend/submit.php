<?php

// Receives form data (POST), validates it and saves it

require_once "config.php";

// Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "errors" => ["Only POST requests are allowed."]
    ]);
    exit;
}

// Get form data
$name = trim($_POST["name"] ?? "");
$enrollment = trim($_POST["enrollment"] ?? "");
$email = trim($_POST["email"] ?? "");
$course = trim($_POST["course"] ?? "");
$gender = trim($_POST["gender"] ?? "");
$marks = $_POST["marks"] ?? "";

// ---- Validation ----
$errors = [];

if ($name === "") {
    $errors[] = "Name is required.";
}

if ($enrollment === "") {
    $errors[] = "Enrollment number is required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Enter a valid email.";
}

$allowedCourses = [
    "B.Tech CSE",
    "BCA",
    "MCA",
    "B.Sc IT"
];

if (!in_array($course, $allowedCourses, true)) {
    $errors[] = "Please select a valid course.";
}

$allowedGenders = [
    "Male",
    "Female",
    "Other"
];

if (!in_array($gender, $allowedGenders, true)) {
    $errors[] = "Please select a valid gender.";
}

if (
    $marks === "" ||
    !is_numeric($marks) ||
    $marks < 0 ||
    $marks > 100
) {
    $errors[] = "Marks must be between 0 and 100.";
}

// Return validation errors
if (!empty($errors)) {
    echo json_encode([
        "success" => false,
        "errors" => $errors
    ]);