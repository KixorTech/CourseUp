<?

$isCourseRoot = TRUE;
if(isset($resouce))
	$isCourseRoot = $resource == '';

if(isset($_GET['resource'])) {
	$resource = $_GET['resource'];
	$isCourseRoot = $resource == '';
}

//include '../CourseUp/Parsedown.php';
require_once('CourseUp/PDExtension.php');
require_once('CourseUp/htmlSchedule.php');

include('header.htm');

if( $isCourseRoot) {
	print '<p><h3>';
	print $config['CourseTitle'];
	print '</h3></p>';
	print '<div class="scheduleTable">';
	$schedule = getHtmlSchedule();
	print $schedule;
	print '</div>';
}
else {
	$contentPath = $resource . '/content.md';
	$f = file_get_contents($contentPath);
	$p = PDExtension::instance()->text($f); 
	//$p = ParseDown::instance()->text($f); 
	echo $p;
}

include('footer.htm');
?>

