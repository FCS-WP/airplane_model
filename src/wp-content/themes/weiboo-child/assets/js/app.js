jQuery(document).ready(function ($) {
  $(".currency-item").on("click", function (event) {
    event.preventDefault();

    // Get the title attribute from the <a> tag within the clicked item
    var title = $(this).find("a").attr("title");
    console.log(title); // Output the title to the console

    // Send AJAX request to change currency
    $.ajax({
      url: '<?php echo admin_url("admin-ajax.php"); ?>',
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        action: "change_currency",
        currency: title, // Use the title for the currency
      }),
      success: function (data) {
        if (data.success) {
          location.reload(); // Reload the page to show updated prices
        }
      },
    });
  });
});
