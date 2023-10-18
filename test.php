https://pixinvent.com/materialize-material-design-admin-template/html/ltr/horizontal-menu-template/index.html





this is form :
<?php echo form_open('class="edit-email-item mt-10 mb-10" id="email-form"'); ?>
				
			<div class="row" style="display:none">		
				<div class="input-field col s12">
					<i class="material-icons prefix">account_circle</i>
					<input type="text" id="from_email" name="from_email" value="<?php echo $session_email." - ".$firstname." ".$lastname; ?>">
					<label for="icon_prefix3">From Email</label>
				</div>
			</div>
			
			
			
			<div class="input-field col s12" style="padding-bottom:20px;">
				<select id="to_email" name="to_email">
					<option value="" disabled selected>Select Client's Email</option>

					<?php foreach ($emails as $email): ?>

					<option value="<?php echo $email['email']; ?> - <?php echo $email['firstname']; ?> <?php echo $email['lastname']; ?>"> <?php echo $email['email']; ?> - <?php echo $email['firstname']; ?> <?php echo $email['lastname']; ?> </option>

					<?php endforeach; ?>

				</select>
				<label for="name2">To Email *</label>
			</div>

			
			<div class="input-field col s12" style="padding-bottom:20px;">
				<input placeholder="Enter Subject" type="text" id="subject" name="subject">
				<label for="name2">Subject *</label>
			</div>
						
									
			<div class="input-field col s12"  style="padding-bottom:20px;">					
				<div class="input-field">
					<select id="cc_emails" name="cc_emails" class="select2 browser-default" multiple="multiple">
						<option value="square">Square</option>
						<option value="rectangle" selected>Rectangle</option>
						<option value="rombo">Rombo</option>
						<option value="romboid">Romboid</option>
						<option value="trapeze">Trapeze</option>
						<option value="traible" selected>Triangle</option>
						<option value="polygon">Polygon</option>
					</select>
				</div>
				<label for="name2">CC</label>
			</div>
							
			<div class="input-field  col s12" style="padding-bottom:20px;">
				<textarea id="message" name="message"></textarea>
			</div>
			
						
			<div class="card-alert card green-card green lighten-5" style="display: none;margin-top: 0 1rem;margin-bottom: 0 1rem;">
				<div class="card-content green-text">
					<p></p>
				</div>
				<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>

			<div class="card-alert card red-card red lighten-5" style="display: none;margin-top: 0 1rem;margin-bottom: 0 1rem;;">
				<div class="card-content red-text">
					<p></p>
				</div>
				<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
				  <span aria-hidden="true"></span>
				</button>
			</div>
			
															
			<div class="card-action pl-0 pr-0 right-align" style="padding-bottom:0px !important">
				<!--
				<button type="reset" class="btn-small waves-effect waves-light cancel-email-item mr-1">
					<i class="material-icons left">close</i>
					<span>Cancel</span>
				</button>
				
				<button type="button" id="draft-btn" class="btn-small waves-effect waves-light mr-1">
					<i class="material-icons left">description</i>
					<span>Draft</span>
				</button>
				-->
				
				<button type="button" id="send-btn" class="btn-small waves-effect waves-light send-email-item">
					<i class="material-icons left">send</i>
					<span>Send</span>
				</button>
					

			</div> 		
		<?php echo form_close(); ?>
		
		
this is ajax : 
<script>
$(document).ready(function() {
	
	// Initialize CKEditor with a custom toolbar
	CKEDITOR.replace('message', {
		
		toolbar: [
			{ name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
			{ name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote', '-', 'Link', 'Placeholder'] }
		]
	});

	$("#send-btn").click(function(event) {
		event.preventDefault();

		// Get the CKEditor instance and its content
		var ckeditorInstance = CKEDITOR.instances.message;
		var ckeditorContent = ckeditorInstance.getData();

		// Set the CKEditor content as the textarea's value
		$("#message").val(ckeditorContent);

		// Rest of your form validation and submission code...
		// (Replace this with your existing validation and AJAX submission code)
		// Example validation:
		var from_email = $("#from_email").val();
		var to_email = $("#to_email").val();
		var subject = $("#subject").val();
		var message = $("#message").val();

		if (from_email.trim() === "") {
			// Display a validation error message
			//displayErrorAlert("Error : Please fill From Email field.");
			//$("#from_email").focus();
			location.reload();
			return;
		}

		if (!to_email) {
			// Display a validation error message
			displayErrorAlert("Error : Select client's email.");
			$("#to_email").focus();
			return;
		}

		if (subject.trim() === "") 
		{
			// Display a validation error message
			displayErrorAlert("Error : Please fill Subject field.");
			$("#subject").focus();
			return;
		}
		
		
		if (message.trim() === "") {
			// Display a validation error message
			displayErrorAlert("Error : Please fill Message field.");
			CKEDITOR.instances.message.focus();
			return;
		}

		// Serialize the form data
		var formData = $(this).serialize();

		// Send an AJAX POST request to the server
		$.ajax({
			type: "POST",
			url: "<?php echo admin_url('Communications/sendCommunications'); ?>",
			data: $("#email-form").serialize(),
			dataType: "json",
			success: function(response) {
				if (response.success) 
				{
					// Clear the form
					$("#email-form")[0].reset();
					$("#to_email").val('');
					$("#to_email").formSelect();
					$("#subject").val('');
					$("#message").val('');
					$("#from_email").focus();
					
					// Display a success message
					//displaySuccessAlert(response.message);
					
					// Hide the email-compose-sidebar
					$(".email-compose-sidebar").hide();
					
					swal({
					  title: "Sent!",
					  text: "Your message has been successfully sent.",
					  icon: "success",
					  buttons: {
						confirm: "OK",
					  },
					}).then(() => {
					  // Page reload when the dialog is closed
					  location.reload();
					});

					// Reload the page after a short delay (e.g., 2 seconds)
					setTimeout(function() 
					{
						location.reload();
					}, 3000); // Adjust the delay as needed

				} 
				else 
				{
					// Display an error message
					displayErrorAlert(response.message);
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				console.error(xhr.responseText);
				// Handle the error here if needed
				displayErrorAlert("An error occurred while processing your request.");
			}
		});
	});

	// Function to display success alert
	function displaySuccessAlert(message) 
	{
		var successAlert = $(".green-card");
		successAlert.find(".card-content p").text(message);
		successAlert.fadeIn();
		
		setTimeout(function() 
		{
			successAlert.fadeOut();
		}, 3000);
	}

	// Function to display error alert
	function displayErrorAlert(message) {
		var errorAlert = $(".red-card");
		errorAlert.find(".card-content p").text(message);
		errorAlert.fadeIn();
		setTimeout(function() {
			errorAlert.fadeOut();
		}, 2000);
	}
});
</script>

model : 

public function insertCommunications($data)
    {
		//$table_name = db_prefix() . 'communications';
        // Insert data into your database table
        
		$this->db->insert(db_prefix() . 'communications', $data);		
		return $this->db->insert_id(); // Return the inserted ID
		

        // No need to return the inserted ID
    }
	
controller:
public function sendCommunications()
	{
		$from_email = $this->input->post('from_email');
		$from_email_parts = explode(" - ", $from_email);
		
		$to_email = $this->input->post('to_email');
		$to_email_parts = explode(" - ", $to_email);

		$cc_emails = $this->input->post('cc_emails');

		// Initialize an array to store the inserted IDs
		$inserted_ids = array();

		// Process each selected cc_email
		foreach ($cc_emails as $cc_email) {
			$cc_email_parts = explode(" - ", $cc_email);

			// Prepare data for insertion
			$data = array(
				'from_email' => $from_email_parts[0],
				'sender_name' => $from_email_parts[1],
				'to_email' => $to_email_parts[0],
				'recipient_name' => $to_email_parts[1],
				'cc_emails' => $cc_email_parts[0],  // This is the 'cc_emails' column
				'cc_name' => $cc_email_parts[1],    // Add a column for cc_name in your database table
				'subject' => $this->input->post('subject'),
				'message' => $this->input->post('message'),
				'is_reply' => FALSE,
			);

			// Insert the data into the communications table
			$inserted_id = $this->CommunicationsModel->insertCommunications($data);

			// Store the inserted ID in the array
			$inserted_ids[] = $inserted_id;
		}

		// Check if any insertions were successful
		if (!empty($inserted_ids)) {
			// Data inserted successfully
			$response['success'] = true;
			$response['message'] = 'Success: Message sent successfully!';
		} else {
			// Insertion failed
			$response['success'] = false;
			$response['message'] = 'Error: Failed to send message. Please try again.';
		}

		// Send the JSON response
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

here use above code data not inserted.

public function sendCommunications()
	{				
		$from_email = $this->input->post('from_email');
		$from_email_parts = explode(" - ", $from_email);
		
		$to_email = $this->input->post('to_email');
		$to_email_parts = explode(" - ", $to_email);
		
		// Client data found; insert data into the communications table
		$data = array(			
			'from_email' => $from_email_parts[0],
			'sender_name' => $from_email_parts[1],
			'to_email' => $to_email_parts[0],
			'recipient_name' => $to_email_parts[1],			
			'subject' => $this->input->post('subject'),
			'message' => $this->input->post('message'),
			'is_reply' => FALSE,
		);

		// Debugging: Check the value of $data
		// echo "Data to be inserted: " . print_r($data, true);

		$inserted_id = $this->CommunicationsModel->insertCommunications($data);
		
		// Set the last insert ID as the thread_id for the original message
		$this->db->where('id', $inserted_id);
		$this->db->update(db_prefix() . 'communications', array('thread_id' => $inserted_id));

		if ($inserted_id > 0) 
		{
			// Data inserted successfully
			$response['success'] = true;
			$response['message'] = 'Success : Message sent successfully!';
		} 
		else 
		{
			// Insertion failed
			$response['success'] = false;
			$response['message'] = 'Error : Failed to send message. Please try again.';
		}

		// Send the JSON response
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}