<?php

    namespace Runtime\App\Template;

    $a_meeting = $this->a_template['a_meeting'];
?>

<h1>Aktuálně pořádaná setkání</h1>
<table class="table table-hover">
    <?php
    if(is_array($a_meeting) && count($a_meeting)) {
        ?>
        <tr>
            <th>Číslo</th>
            <th>Název setkání</th>
            <th>Kdy</th>
        </tr>
        <?php
        foreach($a_meeting as $o_meeting) {
            ?>
            <tr>
                <td><?=$o_meeting->nl_id_meeting;?></td>
                <td><a href="/meeting/detail?nl_id_meeting=<?=$o_meeting->nl_id_meeting;?>" data-id="load_meeting" data-item="<?=$o_meeting->nl_id_meeting;?>"><?=$o_meeting->s_name;?></a></td>
                <td><?=$o_meeting->dt_when;?></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td>Nebyly nalezeny žádná setkání</td>
        </tr>
        <?php
    }
    ?>
</table>


<script type="text/javascript">

    $('*[data-id="load_meeting"]').click(function(e) {
        var nl_id_meeting = $(this).attr('data-item');
        $.ajax( {
            url: '/meeting/detail',
            data: {
                b_ajax: true,
                nl_id_meeting: nl_id_meeting
            },
            success: function(response, status) {
                $('*[data-id="content"]').html(response);
            }
        });
        e.preventDefault();
    })

</script>
