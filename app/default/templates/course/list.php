<?php

    namespace Runtime\App\Template;

    $a_course = $this->a_template['a_course'];
?>

<h1>Aktuální nabídka kurzů</h1>
<table class="table table-hover">
    <?php
    if(is_array($a_course) && count($a_course)) {
        ?>
        <tr>
            <th>Číslo</th>
            <th>Název kurzu</th>
        </tr>
        <?php
        foreach($a_course as $o_course) {
            ?>
            <tr>
                <td><?=$o_course->id_course;?></td>
                <td><a href="/course/detail?nl_id_course=<?=$o_course->id_course;?>" data-id="load_course" data-item="<?=$o_course->id_course;?>"><?=$o_course->s_name;?></a></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td>Nebyly nalezeny žádné kurzy</td>
        </tr>
        <?php
    }
    ?>
</table>


<script type="text/javascript">

    $('*[data-id="load_course"]').click(function(e) {
        var id_course = $(this).attr('data-item');
        $.ajax( {
            url: '/course/detail',
            data: {
                b_ajax: true,
                nl_id_course: id_course
            },
            success: function(response, status) {
                $('*[data-id="content"]').html(response);
            }
        });
        e.preventDefault();
    })

</script>
