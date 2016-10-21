/**
 * Created by ubuntu on 09/10/16.
 */
var totalVote = 0;

$(function() {

    $("#go-vote").prop("disabled", true);

    $(".media-object").mouseenter(function(){
        if (!$(this).hasClass("border-fixe")) {
            $(this).removeClass('padding2');
            $(this).addClass('border-over');
        }
    });

    $(".media-object").mouseleave(function(){
        if (!$(this).hasClass("border-fixe")) {
            $(this).removeClass('border-over');
            $(this).addClass('padding2');
        }
    });

    $(".media-object").click(function(){

        if ($(this).hasClass("border-fixe")){
            totalVote--;
            $(this).removeClass("border-fixe");
            $(this).addClass('padding2');
        } else {
            if (totalVote < 3) {
                totalVote++;
                $(this).addClass("border-fixe");
                $(this).removeClass('border-over');
                $(this).removeClass('padding2');
            }
        }
        $("#vote-number").text(totalVote)
        enableVote();
    });

    $("input[name='name']").keypress(function(){
        enableVote();
    });

    $("#go-vote").click(function(){

        var images = [];

        $(".border-fixe").each(function(){
            images.push($(this).attr("alt"));
        });

        var imageJsonString = JSON.stringify(images);
        var name = $("input[name='name']").val();

        $.ajax({
            method: "POST",
            url: $("#voteUrl").val(),
            data: { "images": imageJsonString,
                    "name": name
            }
        }).done(function( msg ) {
            alert( "Data Saved: " + msg );
        });
    });

    function enableVote(){

        var name = $("input[name='name']").val();
        if (totalVote == 3 && name.length > 0) {
            $("#go-vote").prop("disabled", false);
        } else {
            $("#go-vote").prop("disabled", true);
        }
    }
});
