<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription</title>
    <link rel="stylesheet" href="styles/prescription.css">
</head>
<body>
<?php
$prescription_id = "{{ prescription_id }}";
$med_id = "{{ med_id }}";
?>

<div class="wrapper">
    <div class="prescription_form">
        <table class="prescription" data-prescription_id="<?php echo $prescription_id ?>" border="1">
            <tbody>
                <tr height="15%">
                    <td colspan="2">
                        <div class="header">
                            <div class="logo">
                                <img src="https://seeklogo.com/images/H/hospital-clinic-plus-logo-7916383C7A-seeklogo.com.png">
                            </div>
                            <div class="credentials">
                                <h4>Doctor Name</h4>
                                <p>Chamber Name</p>
                                <small>Address</small>
                                <br>
                                <small>Mb. 0XXXXXXXXX</small>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="40%">
                        <div class="desease_details">
                            <div class="symptoms">
                                <h4 class="d-header">Symptoms</h4>
                                <ul class="symp" data-toggle="tooltip" data-placement="bottom" title="Click to edit." contenteditable="true"></ul>
                                <div class="symp_action">
                                    <button id="symp_save" class="btn btn-sm btn-success save" data-prescription_id="<?php echo $prescription_id ?>">Save</button>
                                    <button class="btn btn-sm btn-danger cancel-btn">Cancel</button>
                                </div>
                            </div>
                            <!-- Other sections -->
                        </div>
                    </td>
                    <td width="60%" valign="top">
                        <!-- Medicine section -->
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="snacking">Saving...</div>
        <div id="snacked">Saved!</div>
    </div>
</div>

<script type="text/javascript" src="prescription.js"></script>
</body>
</html>
