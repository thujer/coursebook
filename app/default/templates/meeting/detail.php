<?php

    namespace Runtime\App\Template;

    $o_result = $this->a_template['o_result'];
    $a_person = $this->a_template['a_person'];
    $a_person_standin = $this->a_template['a_person_standin'];
?>

<div class="page-header">
    <h1><?=$o_result->s_name;?></h1>
</div>

<h2>Detail setkání</h2>
<table class="table table-responsive table-hover">
    <thead>
        <tr>
            <th class="width-sm">Číslo</th>
            <th>Název</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?=$o_result->id_meeting;?></td>
            <td><?=$o_result->s_name;?></td>
        </tr>
    </tbody>
</table>

<hr />
<a href="/meeting/list" data-id="load_all">Zobrazit přehled setkání</a>

<hr />
<h1>Zájemci</h1>
<br />
<h2>Účastníci</h2>
<table class="table table-hover" data-type="wrapper">
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
                <td><?=$o_person->id_person;?></td>
                <td><a href="/person/detail?id_child=<?=$o_person->id_person;?>" data-id="load_person" data-item="<?=$o_person->id_person;?>"><?=$o_person->s_name;?> <?=$o_person->s_lastname;?></a></td>
                <td><?=$o_person->s_phone;?></td>
                <td><?=$o_person->s_email;?></td>
                <td><?=$o_person->s_note;?></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="5">Nebyli nalezeni žádné osoby</td>
        </tr>
        <?php
    }
    ?>
    <tr class="hidden" data-target="add_person">
        <td><input type="hidden" placeholder="" name="nl_id_person" value="0" /></td>
        <td><input type="text" placeholder="Jméno a příjmení" name="s_name" /></td>
        <td><input type="text" placeholder="Váš telefon" name="s_phone" /></td>
        <td><input type="text" placeholder="Váš e-mail" name="s_email" /></td>
        <td><input type="text" placeholder="Poznámka" name="s_note" /> <span data-action="remove_person"><i class="glyphicon glyphicon-remove"></i> Odebrat</span> <span data-action="insert_person"><i class="glyphicon glyphicon-ok"></i> Uložit</span></td>
    </tr>
    <tr data-action="add_person">
        <td colspan="5"><i class="glyphicon glyphicon-plus-sign"></i> Přihlásit se</td>
    </tr>
</table>

<h2>Náhradníci</h2>
<table class="table table-hover" data-type="wrapper">
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
                <td><?=$o_person->id_person;?></td>
                <td><a href="/person/detail?nl_id_person=<?=$o_person->id_person;?>" data-id="load_person" data-item="<?=$o_person->id_person;?>"><?=$o_person->s_name;?> <?=$o_person->s_lastname;?></a></td>
                <td>***<?//=$o_person->s_phone;?></td>
                <td>***<?//=$o_person->s_email;?></td>
                <td><?=$o_person->s_note;?></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <?php
    }
    ?>
    <tr class="hidden" data-target="add_person">
        <td><input type="hidden" placeholder="" name="nl_id_person" value="0" /></td>
        <td><input type="text" placeholder="Jméno a příjmení" name="s_name" /></td>
        <td><input type="text" placeholder="Váš telefon" name="s_phone" /></td>
        <td><input type="text" placeholder="Váš e-mail" name="s_email" /></td>
        <td><input type="text" placeholder="Poznámka" name="s_note" /> <span data-action="remove_person"><i class="glyphicon glyphicon-remove"></i> Odebrat</span> <span data-action="insert_person"><i class="glyphicon glyphicon-ok"></i> Uložit</span></td>
    </tr>
    <tr data-action="add_person">
        <td colspan="5"><i class="glyphicon glyphicon-plus-sign"></i> Přihlásit se</td>
    </tr>
</table>



<script type="text/javascript">

    $('*[data-id="load_all"]').click(function(e) {
        $.ajax( {
            url: '/meeting/list',
            data: {
                b_ajax: true
            },
            success: function(response, status) {
                $('*[data-id="content"]').html(response);
            }
        });
        e.preventDefault();
    });


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
    });


    $('*[data-action="add_person"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function(e) {
        $(this).closest('*[data-type="wrapper"]').find('*[data-target="add_person"]').removeClass('hidden');
    });


    $('*[data-action="remove_person"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function(e) {
        $(this).closest('tr').addClass('hidden');
    })


</script>
