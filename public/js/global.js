function changeStrength(id) {
    console.log($(this).find('option'));
}

$(document).ready(function () {

    $(document).on("dblclick", 'div#home-goal,div#away-goal', function (event) {
        $(this).attr('contentEditable', true);
    });

    $(document).on("blur", 'div#home-goal,div#away-goal', function (event) {
        $(this).attr('contentEditable', true);
        $.get("/api/update-match/" + $(this).data('match-id') + "/" + $(this).attr('id').replace('-', '_') + "/" + $(this)[0].innerHTML, function () {
            refreshFixture();
            refreshLeauge();
            $('#weekly').data('week-id', 1);
            $('#weekly').attr('real', 1);
            getNextMatches();
        });
    });

    $("#show-edit-teams").click(function () {
        $('#edit-teams').show();
        $('#close-edit-teams').show();
        $('#show-edit-teams').hide();
        $('#close-this-div').hide();
        $('#close-this-div2').hide();

    });
    $("#close-edit-teams").click(function () {
        $('#show-edit-teams').show();
        $('#close-this-div').show();
        $('#close-this-div2').show();
        $('#edit-teams').hide();
        $('#close-edit-teams').hide();
    });

    $("#play-all").click(function () {
        $.get("/play-all", function () {
            refreshFixture();
            refreshLeauge();
            $('#weekly').data('week-id', 1);
            $('#weekly').attr('real', 1);
            getNextMatches();
        });
    });
    $("#reset").click(function () {
        $.get("api/reset", function () {
            refreshFixture();
            refreshLeauge();
            $('#weekly').data('week-id', 1);
            getNextMatches();
            $('#weekly').attr('real', 1);
        });
    });
    $("#see-next-week").click(function () {
        getNextMatches();
    });
    $("#play-weekly").click(function () {
        playWeekly();
        refreshFixture();
        refreshLeauge();
    });


    function refreshFixture() {
        $.getJSON("/api/fixture", function (data) {
            console.log(data);
            let showData = $('#table-body');
            showData.empty();
            showData.hide();
            $.each(data.weeks, function (i, week) {
                let html = "";
                html += "<tr><td class=\"table-secondary\" colspan='3'>" + week.name + " Matches</td></tr>";
                $.each(data.items[week.id], function (i, item) {
                    html += "<tr>";
                    html += "<td><img src='http://127.0.0.1:8000/images/" + item.home_logo + "' width='30' height='30' /> " + item.home_team + "</td>";
                    html += "<td>" + item.home_goal + " - ";
                    html += item.away_goal + "</td>";
                    html += "<td><img src='http://127.0.0.1:8000/images/" + item.away_logo + "' width='30' height='30' /> " + item.away_team + "</td>";
                    html += "</tr>";
                });
                showData.append(html);
            });
            showData.show('slow');
        });
    }

    function refreshLeauge() {
        $.getJSON("/api/leauge", function (data) {
            let showData = $('#leauge-table-body');
            showData.empty();
            showData.hide();
            $.each(data, function (i, item) {
                let html = "";
                html += "<tr>";
                html += "<td><img width='50' height='50' src='http://127.0.0.1:8000/images/" + item.logo + "' /> " + item.name + "</td>";
                html += "<td>" + item.points + "</td>";
                html += "<td>" + item.played + "</td>";
                html += "<td>" + item.won + "</td>";
                html += "<td>" + item.draw + "</td>";
                html += "<td>" + item.lose + "</td>";
                html += "<td>" + item.goal_drawn + "</td>";
                html += "<td>" + Math.ceil((Number(item.points) * Number(100)) / 18)  + " %</td>";
                html += "</tr>";
                showData.append(html);
            });
            showData.show('slow');
        });
    }

    function getNextMatches() {
        let weekID = $('table#weekly').data('week-id');
        $.getJSON("/api/next-matches/" + weekID, function (data) {
            let showData = $('#weekly-matches');
            showData.empty();
            showData.hide();
            $.each(data.matches, function (i, item) {
                let html = "";
                if (i == 0) {
                    html += "<tr>"
                        + '<td class="table-secondary" colspan="3">' + item.name + ' Matches</td>'
                        + '</tr>';
                }
                html += '<tr>'
                    + '<td><img width="30" height="30" src="http://127.0.0.1:8000/images/' + item.home_logo + '" /> ' + item.home_team + '</td>'
                    + '<td><div style="float:left" id="home-goal" data-match-id="' + item.id + '">' + item.home_goal + '</div> <div style="float:left" id="scor"> - </div> <div style="float:left" id="away-goal"  data-match-id="' + item.id + '">' + item.away_goal + '</div></td>'
                    + '<td><img width="30" height="30" src="http://127.0.0.1:8000/images/' + item.away_logo + '"/> ' + item.away_team + '</td>'
                    + '</tr>'
                showData.append(html);
                if (item.played == 1)
                    $('#play-weekly').hide();
                else
                    $('#play-weekly').show();
            });
            showData.show('slow');
            if (weekID + 1 == 7) {
                $('#see-next-week').hide();
            }
        });
        $('#weekly').data('week-id', (weekID + 1));
        $('#weekly').attr('real', (parseInt($('#weekly').attr('real')) + 1))
    }

    function playWeekly() {
        let weekId = $('#weekly').attr('real');
        $.getJSON("/api/play-weekly/" + weekId, function (data) {
            let showData = $('#weekly-matches');
            showData.empty();
            showData.hide();
            $.each(data.matches, function (i, item) {
                let html = "";
                if (i == 0) {
                    html += "<tr>"
                        + '<td class="table-secondary" colspan="3">' + item.name + ' Matches</td>'
                        + '</tr>';
                }
                html += '<tr>'
                    + '<td><img width="30" height="30" src="http://127.0.0.1:8000/images/' + item.home_logo + '" /> ' + item.home_team + '</td>'
                    + '<td><span id="home-goal" data-match-id="' + item.id + '">' + item.home_goal + '</span> - <span id="away-goal"  data-match-id="' + item.id + '">' + item.away_goal + '</span></td>'
                    + '<td><img width="30" height="30" src="http://127.0.0.1:8000/images/' + item.away_logo + '" /> ' + item.away_team + '</td>'
                    + '</tr>'
                showData.append(html);
                if (item.played == 1)
                    $('#play-weekly').hide();
            });
            showData.show('slow');
        });
    }
});
