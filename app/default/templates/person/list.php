<?php

    namespace Runtime\App\Template;

    $a_person = $this->a_template['a_person'];
    $a_person_standin = $this->a_template['a_person_standin'];
?>

<h1>Zájemci o setkání</h1>
<br />
<h2>Účastníci</h2>
<table class="table table-hover">
    <?php
    if(is_array($a_person) && count($a_person)) {
        ?>
        <tr>
            <th>Číslo</th>
            <th>Jméno a příjmení</th>
            <th>Telefon</th>
            <th>E-mail</th>
            <th>Poznámka</th>
        </tr>
        <?php
        foreach($a_person as $o_person) {
            ?>
            <tr>
                <td><?=$o_person->nl_id_person;?></td>
                <td><a href="/person/detail?id_child=<?=$o_person->nl_id_person;?>" data-id="load_person" data-item="<?=$o_person->nl_id_person;?>"><?=$o_person->s_name;?> <?=$o_person->s_lastname;?></a></td>
                <td><?=$o_person->s_phone;?></td>
                <td><?=$o_person->s_email;?></td>
                <td><?=$o_person->s_note;?></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td>Nebyli nalezeni žádné osoby</td>
        </tr>
        <?php
    }
    ?>
</table>

<h2>Náhradníci</h2>
<table class="table table-hover">
    <?php
    if(is_array($a_person_standin) && count($a_person_standin)) {
        ?>
        <tr>
            <th>Číslo</th>
            <th>Jméno a příjmení</th>
            <th>Telefon</th>
            <th>E-mail</th>
            <th>Poznámka</th>
        </tr>
        <?php
        foreach($a_person_standin as $o_person) {
            ?>
            <tr>
                <td><?=$o_person->nl_id_person;?></td>
                <td><a href="/person/detail?id_child=<?=$o_person->nl_id_person;?>" data-id="load_person" data-item="<?=$o_person->nl_id_person;?>"><?=$o_person->s_name;?> <?=$o_person->s_lastname;?></a></td>
                <td><?=$o_person->s_phone;?></td>
                <td><?=$o_person->s_email;?></td>
                <td><?=$o_person->s_note;?></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td>Nebyli nalezeni žádné osoby</td>
        </tr>
        <?php
    }
    ?>
</table>

<script type="text/javascript">

    $('*[data-id="load_person"]').click(function(e) {
        var id_child = $(this).attr('data-item');
        $.ajax( {
            url: '/person/detail',
            data: {
                b_ajax: true,
                id_child: id_child
            },
            success: function(response, status) {
                $('*[data-id="content"]').html(response);
            }
        });
        e.preventDefault();
    })

</script>
