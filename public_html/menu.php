<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Menu</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("USER", "ADMIN", "GS", "APPLICANT", "STUDENT", "ALUMNI", "INSTRUCTOR", "ADVISOR", "REVIEWER", "CAC");
include 'header.php';
?>
<h1>Menu</h1>

<?php if (in_array("USER", $_SESSION["roles"]) && !in_array("APPLICANT", $_SESSION["roles"]) && !in_array("ALUMNI", $_SESSION["roles"])) : ?>
<a href='view-info.php'>Update Personal Information</a> <br />
<?php endif; ?>

<?php if (in_array("ADMIN", $_SESSION["roles"])) : ?>
<h3>User Information</h3>
<a href='add-users.php'>Add Users</a> <br />
<a href='update-user-info.php'>Update User Information</a> <br />
<a href='deactivate-user.php'>Deactivate Users</a> <br />
<a href='change-user-roles.php'>Change User Roles</a> <br />
<a href='view-students.php'>View Current Students</a><br />
<a href='view-alumni.php'>View Alumni</a><br />

<h3>Admissions</h3>
<a href='document-status.php'>Update Admissions Document Status</a> <br />
<a href='view-admissions-applications.php'>View Admissions Applications and Reviews</a> <br />
<a href='final-decisions.php'>Update Final Admissions Decisions</a> <br />
<a href='admissions-statistics.php'>View Admissions Statistics</a> <br />

<h3>Student Advising and Records</h3>
<a href='search-student-transcripts.php'>View Student Transcripts</a> <br />
<a href='search-student-grades.php'>Enter Student Grades</a> <br />
<a href='update_advisor_assignments.php'>Update Advisor Assignments</a> <br />
<a href='update_student_holds.php'>Update Student Holds</a> <br />
<a href='approve_deny_graduation_applications.php'>Approve/Deny Graduation Applications</a> <br />

<h3>Classes and Degrees</h3>
<a href='update_degrees.php'>Update Degrees</a> <br />
<a href='update-classes.php'>Update Available Classes</a> <br />
<a href='add-remove-classes.php'>Update Courses Offered</a><br />
<?php endif; ?>

<?php if (in_array("GS", $_SESSION["roles"])) : ?>
<h3>User Information</h3>
<a href='view-user-info.php'>View Student Information</a> <br />
<a href='view-students.php'>View Current Students</a><br />
<a href='view-alumni.php'>View Alumni</a><br />

<h3>Admissions</h3>
<a href='document-status.php'>Update Admissions Document Status</a> <br />
<a href='view-admissions-applications.php'>View Admissions Applications and Reviews</a> <br />
<a href='final-decisions.php'>Update Final Admissions Decisions</a> <br />
<a href='admissions-statistics.php'>View Admissions Statistics</a> <br />

<h3>Student Advising and Records</h3>
<a href='search-student-transcripts.php'>View Student Transcripts</a> <br />
<a href='search-student-grades.php'>Enter Student Grades</a> <br />
<a href='update_advisor_assignments.php'>Update Advisor Assignments</a> <br />
<a href='graduating_students.php'>List of Graduating Students</a><br />
<a href='view_student_holds.php'>View Student Holds</a> <br />
<a href='approve_deny_graduation_applications.php'>Approve/Deny Graduation Applications</a> <br />
<?php endif; ?>

<?php if (in_array("APPLICANT", $_SESSION["roles"])) : ?>
<a href='update-applicant-info.php'>Update Personal Information</a> <br />
<a href='admissions-application.php'>Complete Admissions Application</a> <br />
<a href='manage-recommendations.php'>Manage Letters of Recommendation</a> <br />
<a href='applicant-status.php'>View Application Status</a> <br />
<?php endif; ?>

<?php if (in_array("STUDENT", $_SESSION["roles"])) : ?>
<h3>Classes</h3>
<a href='view-class-schedule.php'>View Class Schedule</a> <br />
<a href='register.php'>Register for Classes</a> <br />
<h3>Advising</h3>
<a href='advising_form.php'>New Student Advising Form</a> <br />
<a href='view-personal-transcript.php'>View Transcript</a> <br />
<a href='view_degree_requirements.php'>View Degree Requirements</a> <br />
<a href='check_degree_status.php'>Check Degree Status</a> <br />
<a href='form1.php'>Apply to Graduate (Form 1)</a> <br />
<?php endif; ?>

<?php if (in_array("ALUMNI", $_SESSION["roles"])) : ?>
<a href='view-alumni-info.php'>Update Personal Information</a> <br />
<a href='view-personal-transcript.php'>View Transcript</a> <br />
<?php endif; ?>

<?php if (in_array("REVIEWER", $_SESSION["roles"])) : ?>
<h3>Admissions</h3>
<a href='completed-applications.php'>Review Applications</a> <br />
<?php endif; ?>

<?php if (in_array("CAC", $_SESSION["roles"])) : ?>
<a href='view-admissions-applications.php'>View Admissions Applications and Reviews</a> <br />
<a href='final-decisions.php'>Update Final Admissions Decisions</a> <br />
<?php endif; ?>

<?php if (in_array("INSTRUCTOR", $_SESSION["roles"])) : ?>
<h3>Classes</h3>
<a href='view-class-roster.php'>View Class Rosters</a> <br />
<a href='search-student-grades.php'>Enter Your Students Grades</a> <br />
<?php endif; ?>

<?php if (in_array("ADVISOR", $_SESSION["roles"])) : ?>
<h3>Student Advising</h3>
<a href='view_advisee_information.php'>View Advisee Information</a> <br />
<a href='advisor-view-transcripts.php'>View Advisee Transcripts</a> <br />
<a href='lift_new_student_hold.php'>Lift New Student Hold</a> <br />
<a href='update_student_holds_advisor.php'>Update Advisee Holds</a> <br />
<?php endif; ?>

</body>
</html>
