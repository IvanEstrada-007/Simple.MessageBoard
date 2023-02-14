<!-- Chapter 6 Message Board project -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Message Board</title>
</head>
<body>
	<h1 style="color: darkcyan;">Message Board</h1>

	<?php

		if(isset($_GET["action"])){
			if(file_exists("messages.txt") === true && filesize("messages.txt") != 0){
				$messageArray = file("messages.txt");

				// switch statement to determine which hyperlink was clicked on
				switch ($_GET["action"]) {
					case "Delete First":
						array_shift($messageArray);
						break;
					case "Delete Last":
						array_pop($messageArray);
						break;	
					case "Delete Message":
						if (isset($_GET["message"])) {
							$index = $_GET['message'];
							unset($messageArray[$index]);
							$messageArray = array_values($messageArray);
						}
						break;
					case "Remove Duplicates":
						$messageArray = array_unique($messageArray);
						$messageArray = array_values($messageArray);
						break;
					case "Sort Ascending":
						sort($messageArray);
						break;
					case "Sort Descending":
						rsort($messageArray);
						break;			
				}// end of switch statement

				//for when ALL messages have been deleted
				if(count($messageArray) > 0){
					$newMessages = implode($messageArray);
					$messageStore = fopen("messages.txt", "w");
					// check that the file can be opened
					if ($messageStore === false) {
						echo "<p>Sorry! There was an error updating the message file!</p>";
					} else {
						fwrite($messageStore, $newMessages);
						fclose($messageStore);
					}// end of file opening statement
				} else {
					// we are here bc $messageArray is empty
					unlink("messages.txt");
				}
			}// end of file checking OF statement

		}// end of initial IF statement


		if (file_exists("messages.txt") === false || filesize("messages.txt") === 0) {
			echo "<p>Sorry, there are no messages posted.<p>";
		} else {
			$messageArray = file("messages.txt");

			echo "<table style='background-color: lightgrey' border='1' width='100%'>\n";
			$count = count($messageArray);
			// loop through each post in the messageArray to build a table row
			for ($i=0; $i < $count; ++$i) { 
			  	$currMsg = explode("~", $messageArray[$i]);
			  	echo "<tr>\n";
			  	echo "<th width='5%'>", ($i + 1), "</th>\n";
			  	echo "<td width='85%'>Name: ", htmlentities($currMsg[0]), "<br/>\n";
			  	echo "<span style='text-decoration: underline; font-weight: bold;'>Subject :</span>", htmlentities($currMsg[1]), "<br/>\n";
			  	echo "<strong>Message:</strong><br/>", htmlentities($currMsg[2]), "</td>\n";
			  	echo "<td width='10%' style='text-align:center;'><a href='messageBoard.php?action=Delete%20Message&message=$i'>Delete This Message</a></td>\n";
			  	echo "</tr>\n";
			  }// end of for loop
			  echo "</table>\n";
		} // end of if/else

	?>

	<p><a href="postMessage.php">Post New Message</a></p>
	<p><a href="messageBoard.php?action=Delete%20First">Delete First Message</a></p>
	<p><a href="messageBoard.php?action=Delete%20Last">Delete Last Message</a></p>
	<p><a href="messageBoard.php?action=Remove%20Duplicates">Remove Duplicate Messages</a></p>
	<p><a href="messageBoard.php?action=Sort%20Ascending">Sort Posts A-Z</a></p>
	<p><a href="messageBoard.php?action=Sort%20Descending">Sort Posts Z-A</a></p>	
</body>
</html>