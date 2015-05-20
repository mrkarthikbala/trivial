trivialApp.controller("rankingController", ['$scope', '$http', function($scope, $http){
	//get rankings
	var getPlayerRankings = function(){
	    $http.get("../phpScripts/getPlayerRankings.php").success(function (response) {  	
		$("#rankingTable").append(response);
		});
	};
	
	var getTeamRankings = function(){
		$http.get("../phpScripts/getRankings.php").success(function(response){
			$("#teamRankingTable").append(response);
		});
	};
	getPlayerRankings();
	getTeamRankings();
	
	
}]);

trivialApp.controller("playController", ['$scope', '$http', function($scope, $http){
	
	$scope.$watch("matchPlay", function(){
	$http.post("../phpScripts/userInMatch.php", {username: $scope.activeUser}).success(function(response){
					$scope.match = null;
					if (response != "None" && $scope.matchPlay=="match"){
						$scope.match = {matchID: response[0], score : response[1], left: response[2], on: response[3]};
						$scope.getNewQuestion();
					}
					else if (response == "None" && $scope.matchPlay == "match"){
						$scope.clear();
						$("#questionSpace").append("You are not in a match. Switching to individual play...");
						setTimeout(function(){$scope.getNewQuestion();}, 2000);
					}
					else{
						$scope.clear();
						$scope.getNewQuestion();
					}
				});
		});
	
	$scope.attemptedUsername = "";
	$scope.attemptedPassword="";
	$scope.loginSuccess= false;
	
	$scope.clear = function(){
		$("#questionSpace").empty();
		
	};
	$scope.login= function(){
		if ($scope.attemptedUsername == "" || $scope.attemptedPassword == "") return;
		$http.post("../phpScripts/login.php",{username: $scope.attemptedUsername, password: $scope.attemptedPassword}).success(function(response){
		if (response == -1){
			alert("Sorry, that user could not be found.");
		}
		else{
			if (response >=1){
				$scope.activeUser = $scope.attemptedUsername;
				$scope.activeUserMMR = Number(response);
				$scope.loginSuccess=true;
				//check if user in match
				$http.post("../phpScripts/userInMatch.php", {username: $scope.activeUser}).success(function(response){
					$scope.match = null;
					if (response != "None" && $scope.matchPlay=="match"){
						$scope.match = {matchID: response[0], score : response[1], left: response[2], on: response[3]};
					}
				});
			}
			else alert("Incorrect Password.");
		}
		
	});
	}
	
	$scope.myAnswer = "";
	$scope.realAnswer = "";
	$scope.showSubmit=false;
	$scope.showOptions = false;
	
	    $scope.submitAnswer = function(){
	    	if ($scope.realAnswer.toLowerCase().indexOf($scope.myAnswer.toLowerCase()) > -1){
	    		alert("Correct!");
	    		$scope.activeUserMMR += Number($scope.activeUserMMR/$scope.realDifficulty + $scope.realDifficulty/5);
	    		if ($scope.activeUserMMR > 999) $scope.activeUserMMR = 999;
	    		if ($scope.match !=null){
	    			//update score
	    			if ($scope.match.on.indexOf("1") > -1){
	    				$scope.match.score += 1; 
	    				$scope.match.left --;
	    			$http.post("../phpScripts/updateMatch.php", {matchID : $scope.match.matchID, score: $scope.match.score, team1Left: $scope.match.left});
	    				
	    			}
	    			else{
	    				$scope.match.score -=1;
	    				$scope.match.left --;
	    				$http.post("../phpScripts/updateMatch.php", {matchID: $scope.match.matchID, score: $scope.match.score, team2Left: $scope.match.left});
	    			}
	    			
	    			
	    		}
	    		$http.post("../phpScripts/addToMMR.php", {username: $scope.activeUser, MMR: $scope.activeUserMMR});
	    	}
	    	else{
	    		alert("Incorrect");
	    		$scope.activeUserMMR -= Number($scope.activeUserMMR/$scope.realDifficulty + $scope.realDifficulty/8);
	    		if ($scope.activeUserMMR < 1) $scope.activeUserMMR = 1;
	    		$http.post("../phpScripts/addToMMR.php", {username:$scope.activeUser, MMR: $scope.activeUserMMR});
	    		
	    		if ($scope.match != null){
	    			if ($scope.match.on.indexOf("1") > -1){
	    				$scope.match.left --;
	    				$http.post("../phpScripts/updateMatch.php", {matchID : $scope.match.matchID, score: $scope.match.score, team1Left: $scope.match.left});
	    				
	    			}
	    			else{
	    				$scope.match.left--;
	    				$http.post("../phpScripts/updateMatch.php", {matchID : $scope.match.matchID, score: $scope.match.score, team2Left: $scope.match.left});
	    			}
	    		}
	    	}
	    $scope.clear();
	    		
	    }; 
	
	$scope.getNewQuestion = function(){
		if (!$scope.loginSuccess) return;
		if ($scope.match != undefined){
			if ($scope.match.left <= 0){
				$scope.clear();
				$("#questionSpace").append("<h6>Your team has answered all questions for the current match.</h6>");
				return;
			}
		}
		$scope.clear();
		var typeOfQuestion = "SAQuestions";
		if (Math.random() > .5) //type of answer changes 
		typeOfQuestion = "MCQuestions"; 
		
		
		/*we want to post the type of question and the users mmr to the following script, which will select a question within the range
			mmr-100 < mmr < mmr + 100.
			Then, we need to get that question back and display it correctly
		*/
		if (typeOfQuestion ==="SAQuestions"){
			$http({
				method: 'POST',
				url :"../phpScripts/getNewQuestion.php",
				data : {qType : typeOfQuestion, MMR: $scope.activeUserMMR },
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				})
			.success(function(response){
				//question, answer, difficulty, subject
				$scope.realAnswer = response[1];
				$scope.realDifficulty = Number(response[2]);
				
				$("#questionSpace").append(response[0]);
				
				$scope.showSubmit = true;
				$scope.showOptions=false;
					
			
			});
		}
		else{
			$http({
				method: 'POST',
				url: "../phpScripts/getMCQuestion.php",
				data : {qType : typeOfQuestion, MMR: $scope.activeUserMMR},
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				})
			.success(function(response){
				//question, answer, difficulty, subject, a, b, c, d
				$scope.realAnswer = response[1];
				$scope.realDifficulty = Number(response[2]);
				$scope.choiceA = response[4];
				$scope.choiceB = response[5];
				$scope.choiceC = response[6];
				$scope.choiceD = response[7];
				$("#questionSpace").append(response[0]);
				$scope.showSubmit = false;
				$scope.showOptions=true;
				
				});
				
		}
	};
	
}]);

trivialApp.controller("matchController", ['$scope', '$http', function($scope, $http){
	$scope.team1 = undefined;
	$scope.team2 = undefined;
	$scope.numQuestionTotal = undefined;
	
	$http.get("../phpScripts/getTeams.php").success(function(response){
		$scope.teams = response;
	});
	$scope.numQ = [];
	for (var i = 2; i < 100; i+=2){
		$scope.numQ.push(i);
	}
	$scope.createMatch= function(){
		if ($scope.teams.indexOf($scope.team1) == -1) alert("Team 1 not selected");
		if ($scope.teams.indexOf($scope.team2) == -1) alert("Team 2 not selected");
		
		if ($scope.team1 == $scope.team2) alert("Teams 1 and 2 are the same");
		if ($scope.numQuestionTotal == undefined) alert("Select a number of questions for the match.");
		
		//matches should have team1, team2, score, numQuestionTotal, questionsLeft1, questionsLeft2
		$http({ method: 'POST',
			url: "../phpScripts/createNewMatch.php",
			data: {team1: $scope.team1, team2: $scope.team2, score:0, TotalQuestions: $scope.numQuestionTotal, Team1Left: $scope.numQuestionTotal/2,Team2Left: $scope.numQuestionTotal/2 },
			
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			});
		
	};
	
	$http.get("../phpScripts/getCurrentMatches.php").success(function(response){
		$("#currentMatches").append(response);
	});
	$http.get("../phpScripts/getMatches.php").success(function(response){
		$("#allMatches").append(response);
	});
}]);