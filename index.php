<?php
	function getPuzzle($url) {
		#Split URL into parts
    	$parts = parse_url($url, PHP_URL_QUERY);
		parse_str($parts, $params);
		#Decode URL
		$puzzle = preg_replace( "/%u([0-9a-f]{3,4})/i","&#x\\1;", urldecode($params['d']));
		return html_entity_decode($puzzle, null, 'UTF-8');
    }

    function getInitialPattern($pattern){
    	for ($i = 0; $i < strlen($pattern); $i++) {
    		#Swap each carrot with opposite
    		if ($pattern[$i] == '>') {
    			$pattern[$i] = '<';
    		}
    		#Both '=' and '<' need to be replaced with '>'
    		else{
    		 	$pattern[$i] = '>';
    		}
    	}
    	return $pattern;
    }

    function createPuzzleResult($matrix, $char_puzzles){
    	#Add cascading '=' to matrix and modify carrots for a charset if it's char puzzle dictates
    	for ($i = 0; $i < count($matrix); $i++) {
    		$matrix = addCascadingEquals($matrix, $i);
    		#Swap carrots if charset puzzle has different value 
    		#than current carrot in current row
    		$currPuzzle =  $char_puzzles[$i];
    		$matrix = swapCarrotIfDifferent($currPuzzle, $matrix, $i);
    	}
    	return $matrix;
    }

    function addCascadingEquals($matrix, $i){
		$temp_pattern = $matrix[$i];
		$temp_pattern[$i] = '=';
		$matrix[$i] = $temp_pattern;
    	return $matrix;
    }

    function swapCarrotIfDifferent($currPuzzle, $matrix, $i){
    	for ($j = 0; $j < strlen($currPuzzle); $j++){
			if ($currPuzzle[$j] != '-' && $matrix[$i][$j] != '=' && $currPuzzle[$j] != $matrix[$i][$j]){
				$matrix[$i][$j] = $currPuzzle[$j];
			}
		}
		return $matrix;
    }

    function prettyPrint($matrix, $indexToNum){
    	echo " ABCD";
    	for ($i = 0; $i < count($matrix); $i++) { 
    		echo $indexToNum[$i].$matrix[$i];
    	}
    }

	if ($_GET["q"] == "Ping"){
		echo "OK";
	}

	if ($_GET["q"] == "Name"){
		echo "Alison P. Foster";
	}

	if ($_GET["q"] == "Referrer"){
		echo "I was reached out to by a recruiter on the Hired.com website";
	}

	if ($_GET["q"] == "Resume"){
		echo "Resume - http://68.173.85.81/resume.php,";
		echo "Cover Letter - http://68.173.85.81/cover_letter.php";
	}

	if ($_GET["q"] == "Email Address"){
		echo "alipaigefoster@gmail.com";
	}

	if ($_GET["q"] == "Years"){
		echo "1.5 years";
	}

	if ($_GET["q"] == "Source"){
		echo "Source Code For Submission - https://github.com/alifost/WebServerSubmission.git";
	}

	if ($_GET["q"] == "Phone"){
		echo "(323) 404-3535";
	}

	if ($_GET["q"] == "Status"){
		echo "Yes";
	}

	if ($_GET["q"] == "Degree"){
		echo "Bachelor of Science in Computer Science from the University of Michigan";
	}

	if ($_GET["q"] == "Position"){
		echo "I am applying for the software engineering position for bRealTime.";
	}
	
	if ($_GET["q"] == "Puzzle"){
		$puzzle_str = getPuzzle($_SERVER['REQUEST_URI']);

		$puzzle = preg_replace('/[a-zA-z\:]/', '', $puzzle_str);
		$char_puzzles = preg_split('/\s+/', $puzzle, -1, PREG_SPLIT_NO_EMPTY);

		$initial_pattern = preg_replace('/[^\>\<\=]/', '', $puzzle);
		$intitial_pattern = getInitialPattern($initial_pattern);

		$matrix = array(
			0 => $intitial_pattern,
			1 => $intitial_pattern,
			2 => $intitial_pattern,
			3 => $intitial_pattern,
			);
		$indexToNum = array(
			0 => "A",
			1 => "B",
			2 => "C",
			3 => "D",
			);

	    $matrix = createPuzzleResult($matrix, $char_puzzles);
	    prettyPrint($matrix, $indexToNum);
	}
?>