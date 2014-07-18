<?php
/*  Collector
    A program for running experiments on the web
    Copyright 2012-2014 Mikey Garcia & Nate Kornell
 */
    require 'initiateCollector.php';

    // if someone skipped to done.php without doing all trials
    if (array_key_exists('finishedTrials', $_SESSION)) {
        if ($_SESSION['finishedTrials'] != TRUE) {
            header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");            // rick roll people trying to skip to done.php
            exit;
        }
    }

    if (array_key_exists('Debug', $_SESSION)) {
        if ($_SESSION['Debug'] == FALSE) {
            error_reporting(0);
        }
    }

    #### TO-DO ####
    $finalNotes = '';
    /*
     * Write code that looks at previous logging in activity and gives recommendations as to whether or not to include someone
     * ideas:
     *        if someone has logged in more than once, flag them
     *        if someone has 1 login and no ends then say they're likely good
     *        if someone already has 1 finish then say so
     */
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/global.css" rel="stylesheet" type="text/css" />
    <title>Done!</title>
</head>
<body>
<?php
    // Set the page message
    if ($nextExperiment == FALSE) {
        $message = "<h2>Thank you for your participation!</h2>
                   <p>If you have any questions about the experiment please email
                       <a href='mailto:{$experimenterEmail}?Subject=Comments%20on%20{$experimentName}' target='_top'>{$experimenterEmail}</a>
                   </p>";
        if ($mTurkMode == TRUE) {
            $message .= "<h3>Your verification code is: {$verification}</h3>";
        }
    } else {
        $message  = "<h2>Experiment will resume in 5 seconds.</h2>";
        $nextLink = 'http://'.$nextExperiment;
        $username = $_SESSION['Debug'] ? $debugName.' '.$_SESSION['Username'] : $_SESSION['Username'];
        echo '<meta http-equiv="refresh" content="5; url=' . $nextLink . 'Code/login.php?Username=' . urlencode($username) . '&Condition=Auto&ID=' . $_SESSION['ID'] . '">';
    }
	
    if (isset($_SESSION['finishedTrials'])) {
        $duration = time() - strtotime($_SESSION['Start Time']);
        $durationFormatted = $duration;
        $hours   = floor($durationFormatted/3600);
        $minutes = floor( ($durationFormatted - $hours*3600)/60);
        $seconds = $durationFormatted - $hours*3600 - $minutes*60;
        if ($hours   < 10 ) { $hours   = '0' . $hours;   }
        if ($minutes < 10 ) { $minutes = '0' . $minutes; }
        if ($seconds < 10 ) { $seconds = '0' . $seconds; }
        $durationFormatted = $hours . ':' . $minutes . ':' . $seconds;
        #### Record info about the person ending the experiment to status finish file
        $data = array(  
                            'Username'              => $_SESSION['Username'],
                            'ID'                    => $_SESSION['ID'],
                            'Date'                  => date('c'),
                            'Duration'              => $duration,
                            'Duration_Formatted'    => $durationFormatted,
                            'Session'               => $_SESSION['Session'],
                            'Condition_Number'      => $_SESSION['Condition']['Number'],
                            'Inclusion Notes'       => $finalNotes
                         );
        arrayToLine($data, $statusEndPath);
    }
    ########
    
    ######## Save the session variable as a JSON Encode
    
    
    
    #######
    $_SESSION = array();                        // clear out all session info
    session_destroy();                          // destroy the session so it doesn't interfere with any future experiments
	
	
	$title = 'Done!';
    require $_codeF . 'Header.php';
?>
	<div class="cframe-content">
		<?php echo $message; ?>
	</div>
<?php
    require $_codeF . 'Footer.php';