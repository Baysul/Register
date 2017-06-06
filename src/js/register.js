$(function() {

	var successIcon = $("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>")
	var errorIcon = $("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>")

	// This shit is gross
	var handleSubmissionSuccess = function(data) {
		var alertMessage = (data["success"] ? successIcon : errorIcon);

		$(".alert-message").html(alertMessage).append(data["message"]);

		if(data["success"]) {
			$("#success").fadeToggle();
		} else {
			$("button[type=submit]").prop("disabled", false);
			$("#error").fadeToggle();
		}
	}

	var handleSubmissionFailure = function() {
		$(".alert-message")
			.html(errorIcon)
			.append("<strong>Uh oh!</strong> An error occurred while attempting to register your penguin.");

		$("#error").fadeToggle();
		$("button[type=submit]").prop("disabled", false);
	}

	var handleSubmission = function(event) {
		$(".alert:visible").fadeToggle();

		var registrationData = {
			username: $("#username").val(),
			password: $("#password").val(),
			color: $("#color").val(),
			captcha: $(".g-recaptcha-response").val()
		}

		$("button[type=submit]").prop("disabled", true);

		$.post("register.php", registrationData, handleSubmissionSuccess, "json")
			.fail(handleSubmissionFailure);

		event.preventDefault();
	}

	$("form").submit(handleSubmission);
});