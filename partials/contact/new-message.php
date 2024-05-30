<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="new-message-inputs">

    <div class="textarea-container">
        <textarea name="conversation_new_message" id="autoresizing" oninput="autoResizeTextarea(this)" rows="1"></textarea>
    </div>

    <div class="submit-container">
        <button type="submit" name="add-message" id="add-message">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <polygon points="7.293 4.707 14.586 12 7.293 19.293 8.707 20.707 17.414 12 8.707 3.293 7.293 4.707"/>
            </svg>
        </button>
    </div>

</div>

