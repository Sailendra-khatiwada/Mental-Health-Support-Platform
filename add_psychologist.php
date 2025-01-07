<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['psychologist_name'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $specialization = $_POST['specialization'];
    $location = $_POST['location'];
    $education = $_POST['education'];
    $min_fee = $_POST['min_fee'];
    $max_fee = $_POST['max_fee'];
    $office_start = $_POST['office_start'];
    $office_end = $_POST['office_end'];
    $contact_info = $_POST['contact_info'];

    if (!$email) {
        echo "<script>alert('Invalid email address');</script>";
        exit;
    }

    $sql = "INSERT INTO psychologist (username, email, password, specialization, location, education, min_fee, max_fee, office_start, office_end, contact_info) 
            VALUES ('$name', '$email', '$password', '$specialization', '$location', '$education', '$min_fee', '$max_fee', '$office_start', '$office_end', '$contact_info')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Psychologist added successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
