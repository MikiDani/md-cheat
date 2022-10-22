<?php
if (isset($_COOKIE['login'])) {
?>
<div class="container m-0 p-0">
    <div class="row m-0 p-0">
        <!-- cheatmonitor -->
        <div class="row m-0 p-0" id="cheatmonitor"></div>

        <div class="col-10 m-0 p-0">
            <input type="text" name="newmessage" id="newmessage" class="form-control form-control-sm" placeholder="message..." autocomplete="off">
        </div>
        <div class="col-2 m-0 p-0 text-center">
            <button type="submit" class="btn btn-primary btn-sm shadow" id="newmessage_submit">Send</button>
        </div>
    </div>
</div>

<?php } ?>
<script src="js/jquery.min.js" referrerpolicy="no-referrer"></script>
<script>
    $(function() {

        function commentListing() {
            $.ajax({
                url: "cheatmonitor-cheat.php",
                success: function(data){
                    $("#cheatmonitor").html(data);
                }
            })
        }

        //first load
        commentListing();

        setInterval(function() { commentListing(); }, 2000);

        $("#newmessage_submit").click(function() {

            var newmessage = $("#newmessage").val();

            $.ajax({
                method: "POST",
                url: "newmessage-cheat.php",
                data: {newmessage: newmessage},
                success: function() {
                    commentListing();
                }
            })

            $("#newmessage").val("");
        })

    })
</script>