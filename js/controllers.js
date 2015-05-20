trivialApp.controller("userController", ['$scope', '$http', function($scope, $http){
	
	$scope.username = "";
	$scope.searchUsername = "";
	$scope.password = "";
	$scope.email = "";
	
	$scope.deleteUsername = "";

	$scope.response = "";
	
	$scope.signUp = function(){
		if ($scope.username == "username" || $scope.email == "email" || $scope.password == "password"){
			alert("Please enter a valid username, email, and password combination.");
			return;
		}
		var FormData = {email : $scope.email, username: $scope.username, password: $scope.password};
		$http({
			method: 'POST',
			url: '../phpScripts/searchUserByEmail.php',
			data: {email: $scope.email},
			headers: {'Content-Type': 'application/json'}
			}).success(function(response){
				if (!(response.indexOf("None-Email")> -1)){
					alert("That email has been taken.");
					
				}
				else{
				
		$http({
			method: 'POST',
			url: '../phpScripts/searchUser.php',
			data: {username: $scope.username},
			headers: {'Content-Type': 'application/json'}
			}).success(function(response){
				if (!(response.indexOf("None-Username")>-1)){
					alert("That username has been taken.");
				}
				else{
						$http({
			     		method: 'POST',
			      		url: '../phpScripts/insertUser.php',
			      		data: FormData,
			      		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			    	}).
				success(function(response) {
				alert("Signed Up!");
				}).
				error(function(response) {
					alert("A user with that email exists.");
				});
		}
				
			});
		
		}
	});	
	};
	$scope.searchUser = function(){
		$http({
			method: 'POST',
			url: '../phpScripts/searchUser.php',
			data: {username: $scope.searchUsername},
			headers: {'Content-Type': 'application/json'}
			}).success(function(response){
				if (response.indexOf("None") > -1) alert("No such user exists.");
				else alert("Email: " +response[0]);
			});
		};
	$scope.updateUser = function(){
		var FormData = {email : $scope.email, username: $scope.username, password: $scope.password};
		$http({
			method: 'PUT',
			url: '../phpScripts/updateUser.php',
			data: FormData,
			headers: {'Content-Type': 'application/json'}
			})
			.success(function(response){
				alert("User Updated!");
				});
		};
	
	$scope.deleteUser = function(){
		$http({
			method: 'POST',
			url: '../phpScripts/searchUser.php',
			data: {username: $scope.deleteUsername},
			headers: {'Content-Type': 'application/json'}
			}).success(function(response){
				if (response.indexOf("None") > -1){
				 alert("No such user exists.");
				 return;
				 }
			$http({
			method: 'DELETE',
			url: '../phpScripts/deleteUser.php',
			data: {username: $scope.deleteUsername},
			headers: {'Content-Type': 'application/json'}
			}).success(function(response){
				alert("User Deleted!");
				}); 
			});
		
		
		};
			
}]);
trivialApp.controller("teamController", ['$scope', '$http', function($scope, $http){
	
	$scope.teamName = "";
	//init email 1 through 5
	$scope.email1 = "";
	$scope.email2 = "";
	$scope.email3 = "";
	$scope.email4 = "";
	$scope.email5 = "";
	$scope.userEmail = "";
	$scope.quitterEmail = "";
	
	$scope.checkValidEmails = function(){
		if ($scope.email1.length > 0) $http.post('../phpScripts/searchUserByEmail.php', {email: $scope.email1}).success(function(response){
			if (response.indexOf("None") > -1) alert("Email 1 is invalid.");
			return false;
			});
		if ($scope.email2.length > 0) $http.post('../phpScripts/searchUserByEmail.php', {email:$scope.email2}).success(function(response){
			if (response.indexOf("None") > -1) alert("Email 2 is invalid.");
			return false;
		
		});
		if ($scope.email3.length > 0) $http.post('../phpScripts/searchUserByEmail.php',{email: $scope.email3}).success(function(response){
			if (response.indexOf("None") > -1) alert("Email 3 is invalid.");
			return false;
			});
		if ($scope.email4.length>0) $http.post('../phpScripts/searchUserByEmail.php', {email:$scope.email4}).success(function(response){
			if (response.indexOf("None") > -1) alert("Email 4 is invalid.");
			return false;
			});
		if ($scope.email5.length > 0) $http.post('../phpScripts/searchUserByEmail.php', {email: $scope.email5}).success(function(response){
			if (response.indexOf("None") > -1) alert("Email 5 is invalid.");
			return false;
			});
			return true;
	}
	$scope.createTeam = function(){
	//
		var FormData = {teamName : $scope.teamName, emails : [$scope.email1, $scope.email2, $scope.email3, $scope.email4, $scope.email5]};
		
		for (var i = 0; i < FormData.emails.length; i ++){
			if (FormData.emails[i].indexOf("Email ") > -1){
				FormData.emails[i] = null;
			}
		}
		if (!$scope.checkValidEmails() ) return;	
		$http({
			method: 'POST',
			url: '../phpScripts/insertTeam.php',
			data: FormData,
			headers: {'Content-Type':'application/x-www-form-urlencoded'}
			}).
		success(function(response){
			if (response.indexOf("must") > -1) alert(response);
			else alert("Team " + response + " Created!");
			})
		.error(function(response){
			alert("Error occured.");
			});
			
	};
	
	$scope.findTeam = function(){
		$http({
			method: 'POST',
			url: '../phpScripts/findTeam.php',
			data: {userEmail: $scope.userEmail},
			headers: {'Content-Type':'application/x-www-form-urlencoded'}
			}).success(function(response){
				if (response == undefined) alert("No such user exists.");
				else alert("Team: " + response);
			});
		};
		
		
	$scope.leaveTeam = function(){
	
		$http({
			method: 'POST',
			url: '../phpScripts/leaveTeam.php',
			data: {quitterEmail: $scope.quitterEmail, leaveTeamName: $scope.leaveTeamName},
			headers: {'Content-Type':'application/x-www-form-urlencoded'}
			}).success(function(response){
				if (response.indexOf("Please") > -1) alert(response);
				else alert("Team " + response + " left!");
			});
	
		};
}]);
trivialApp.controller("questionController", ['$scope', '$http', function($scope, $http){
	
	$scope.subject = "";
	$scope.type = "";
	$scope.question = "";
	$scope.A = "";
	$scope.B = "";
	$scope.C = "";
	$scope.D = "";
	$scope.answer = "";
	$scope.difficulty = 500;
	
	$scope.addQuestion = function(){
	
		var FormData = {
			subject: $scope.subject, 
			type : $scope.type, 
			question : $scope.question,
			difficulty: $scope.difficulty,
			A : $scope.A,
			B : $scope.B,
			C : $scope.C,
			D : $scope.D,
			answer : $scope.answer,
		};
		$http({
	     		method: 'POST',
	      		url: '../phpScripts/addQuestion.php',
	      		data: FormData,
	      		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    	}).
		success(function(response) {
			alert(response);
		}).
		error(function(response) {
		});
	};	
}]);
trivialApp.controller("searchController", ['$scope', '$http', function($scope, $http){
	
	$scope.subject = "";
	$scope.type = "";
	$scope.keyword = "";
	$scope.limit = "";
	$scope.numQ = [];
	$scope.results = "";
	for (var i = 1; i < 100; i+=1){
		$scope.numQ.push(i);
	}
	
	$scope.findQuestions = function(){
		var FormData = {
			subject: $scope.subject, 
			type : $scope.type, 
			keyword : $scope.keyword,
			limit : $scope.limit,
		};
		$http({
	     		method: 'POST',
	      		url: '../phpScripts/findQuestions.php',
	      		data: FormData,
	      		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    	}).
		success(function(response) {
			$scope.results = response;
		}).
		error(function(response) {
		});
	};	
}]);