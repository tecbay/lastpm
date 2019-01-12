<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
</head>
<body>
<form id="ajax_url">
    @csrf
    <select name="method" id="method">
        <option value="get">Get</option>
        <option value="post">Post</option>
    </select>
    <input id="url" style="width: 800px"/>

    <button type="submit">Send</button>
</form>

<pre id="viewer"><div/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="application/javascript">
    $(document).ready(function () {
        var base_url = window.location.origin;
        var url = $('#url').val(base_url + "\/");
    });
    $("#ajax_url").submit(function () {
        event.preventDefault();
        var method = $('#method').val();
        var url = $('#url').val();
        $.ajax({
            url: url,
            method: method,
            dataType: 'json',
            success: function (d) {
                console.log(d);
                $('#viewer').html(JSON.stringify(d))
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(JSON.parse(xhr.responseText));
                $('#viewer').html(JSON.parse(xhr.responseText));
            }
        });
    });
</script>
</body>
</html>
