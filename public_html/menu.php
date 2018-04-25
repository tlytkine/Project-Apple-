<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Menu</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("USER");
include 'header.php';
?>
<h1>Menu</h1>

<?php if (in_array("USER", $_SESSION["roles"])) : ?> <!-- -applicant/-alumni -->
<a href=''>Update Personal Information</a> <br />
<?php endif; ?>

<?php if (in_array("ADMIN", $_SESSION["roles"])) : ?>
<a href=''>Add Users</a> <br />
<a href=''>Update User Information</a> <br />
<a href=''>Deactivate Users</a> <br />
<a href=''>Change User Roles</a> <br />

<a href=''>Update Admissions Document Status</a> <br />
<a href=''>View Admissions Applications and Reviews</a> <br />
<a href=''>Update Final Admissions Decisions</a> <br />

<a href=''>View Student Transcripts</a> <br />
<a href=''>Enter Student Grades</a> <br />
<a href='update_advisor_assignments.php'>Update Advisor Assignments</a> <br />
<a href=''>Update Student Holds</a> <br />
<a href=''>Approve/Deny Graduation Applications</a> <br />

<a href=''>Update Degrees</a> <br />
<a href=''>Update Available Classes</a> <br />
<a href=''>Update Courses Offered</a><br />
<?php endif; ?>

<?php if (in_array("GS", $_SESSION["roles"])) : ?>
<a href=''>View Student Information</a> <br />

<a href=''>Update Admissions Document Status</a> <br />
<a href=''>View Admissions Applications and Reviews</a> <br />
<a href=''>Update Final Admissions Decisions</a> <br />

<a href=''>View Student Transcripts</a> <br />
<a href=''>Enter Student Grades</a> <br />
<a href='update_advisor_assignments.php'>Update Advisor Assignments</a> <br />
<a href=''>View Student Holds</a> <br />
<a href=''>Approve/Deny Graduation Applications</a> <br />
<?php endif; ?>

<?php if (in_array("APPLICANT", $_SESSION["roles"])) : ?>
<a href=''>Update Personal Information</a> <br />
<a href=''>View Admissions Application</a> <br />
<a href=''>View Application Status</a> <br />
<?php endif; ?>

<?php if (in_array("STUDENT", $_SESSION["roles"])) : ?>
<a href=''>View Class Schedule</a> <br />
<a href=''>View Transcript</a> <br />
<a href=''>Register for Classes</a> <br />
<a href=''>View Degree Requirements</a> <br />
<a href=''>Apply to Graduate</a> <br />
<?php endif; ?>

<?php if (in_array("ALUMNI", $_SESSION["roles"])) : ?>
<a href=''>Update Personal Information</a> <br />
<a href=''>View Transcript</a> <br />
<?php endif; ?>

<?php if (in_array("INSTRUCTOR", $_SESSION["roles"])) : ?>
<a href=''>View Class Rosters</a> <br />
<a href=''>Enter Student Grades</a> <br />
<a href=''>View Student Transcripts</a> <br />
<?php endif; ?>

<?php if (in_array("ADVISOR", $_SESSION["roles"])) : ?>
<a href=''>View Advisee Information</a> <br /> <!-- includes application status -->
<a href=''>View Advisee Transcripts</a> <br />
<a href=''>Update Advisee Holds</a> <br />
<?php endif; ?>

<?php if (in_array("REVIEWER", $_SESSION["roles"])) : ?>
<a href=''>Review Applications</a> <br />
<?php endif; ?>

<?php if (in_array("CAC", $_SESSION["roles"])) : ?>
<a href=''>View Admissions Applications and Reviews</a> <br />
<a href=''>Update Final Admissions Decisions</a> <br />
<?php endif; ?>

</body>
</html>