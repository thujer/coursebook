<?php

    namespace Runtime\App\Template;

    $o_result = $this->a_template['o_result'];
    $a_person = $this->a_template['a_person'];
    $a_person_standin = $this->a_template['a_person_standin'];
?>

<div class="page-header">
    <h1><?=$o_result->s_name;?></h1>
    <p>Datum konání: <?=$o_result->dt_when;?></p>
    <p><?=$o_result->s_detail;?></p>
</div>

<form>
<h2>Účastníci</h2>
<p>Maximální počet lidí: <?=$o_result->nl_people_max;?></p>
<table class="table table-hover" data-type="wrapper">
    <?php
    if(is_array($a_person) && count($a_person)) {
        ?>
        <tr>
            <th>Číslo</th>
            <th>Jméno a příjmení</th>
            <!--<th>Telefon</th>
            <th>E-mail</th>-->
            <th>Poznámka</th>
        </tr>
        <?php
        $nl_ix = 0;
        foreach($a_person as $o_person) {
            ?>
            <tr data-nl_id_person="<?=$o_person->nl_id_person;?>">
                <td><?=++$nl_ix;?></td>
                <td><!--<a href="/person/detail?nl_id_person=<?=$o_person->nl_id_person;?>" data-id="load_person" data-item="<?=$o_person->nl_id_person;?>">--><?=$o_person->s_name;?> <?=$o_person->s_lastname;?><!--</a>--></td>
                <!--<td><?=$o_person->s_phone;?></td>
                <td><?=$o_person->s_email;?></td>-->
                <td><?=$o_person->s_note;?></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="5">Nebyli nalezeny žádné osoby</td>
        </tr>
        <?php
    }
    ?>
    <tr class="hidden" data-target="add_person">
        <td colspan="3">
            <input type="hidden" placeholder="" name="nl_id_person" value="0" />
            <input type="text" placeholder="Jméno a příjmení" name="s_name" />
            <input type="text" placeholder="Váš telefon" name="s_phone" />
            <input type="text" placeholder="Váš e-mail" name="s_email" />
            <input type="text" placeholder="Poznámka" name="s_note" />
            <span class="panel">
                <span data-action="store_person"><i class="glyphicon glyphicon-ok"></i> Uložit</span> |
                <span data-action="remove_person"><i class="glyphicon glyphicon-remove"></i> Odebrat / zrušit</span>
            </span>
        </td>
    </tr>
    <?php
        if(count($a_person) < $o_result->nl_people_max) {
            ?>
            <tr data-action="add_person">
                <td colspan="6"><i class="glyphicon glyphicon-plus-sign"></i> Přidat se</td>
            </tr>
            <?php
        }
    ?>
</table>

    <?php
    if(count($a_person) >= $o_result->nl_people_max) {
        ?>
        <p>Dosaženo maximálního počtu účastníků, pokud máš zájem, můžeš se přidat jako náhradník. V případě uvolnění místa budeš informován e-mailem.</p>
        <?php
    }
    ?>
</form>

<?php
//echo count($a_person) . ' vs ' . $o_result->nl_people_max;
if(count($a_person) >= $o_result->nl_people_max) {
    ?>
    <form>
        <h2>Náhradníci</h2>
        <table class="table table-hover" data-type="wrapper">
            <?php
            if (is_array($a_person_standin) && count($a_person_standin)) {
                ?>
                <tr>
                    <th>Číslo</th>
                    <th>Jméno a příjmení</th>
                    <!--<th>Telefon</th>
                    <th>E-mail</th>-->
                    <th>Poznámka</th>
                </tr>
                <?php
                foreach ($a_person_standin as $o_person) {
                    ?>
                    <tr data-nl_id_person="<?= $o_person->nl_id_person; ?>">
                        <td><?= ++$nl_ix; ?></td>
                        <td>
                            <!--<a href="/person/detail?nl_id_person=<?= $o_person->nl_id_person; ?>" data-id="load_person" data-item="<?= $o_person->nl_id_person; ?>">--><?= $o_person->s_name; ?>
                            <?= $o_person->s_lastname; ?><!--</a>--></td>
                        <!--<td>***<?//=$o_person->s_phone;?></td>
                    <td>***<?//=$o_person->s_email;?></td>-->
                        <td><?= $o_person->s_note; ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <?php
            }
            ?>
            <tr class="hidden" data-target="add_person">
                <td colspan="3">
                    <input type="hidden" placeholder="" name="nl_id_person" value="0"/>
                    <input type="text" placeholder="Jméno a příjmení" name="s_name"/>
                    <input type="text" placeholder="Váš telefon" name="s_phone"/>
                    <input type="text" placeholder="Váš e-mail" name="s_email"/>
                    <input type="text" placeholder="Poznámka" name="s_note"/>
                <span class="panel">
                    <span data-action="store_person"><i class="glyphicon glyphicon-ok"></i> Uložit</span> |
                    <span data-action="remove_person"><i class="glyphicon glyphicon-remove"></i> Odebrat / zrušit</span>
                </span>
                </td>
            </tr>
            <tr data-action="add_person">
                <td colspan="6"><i class="glyphicon glyphicon-plus-sign"></i> Přidat se</td>
            </tr>
        </table>
    </form>
    <?php
}
?>

<hr />
<a href="/meeting/list" data-id="load_all">Zpět na přehled setkání</a>


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
        var e_wrapper = $(this).closest('*[data-type="wrapper"]');
        $(e_wrapper).find('*[data-target="add_person"]').removeClass('hidden');
        $(e_wrapper).find('*[data-action="add_person"]').addClass('hidden');
    });


    $('*[data-action="remove_person"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function(e) {
        var e_wrapper = $(this).closest('*[data-type="wrapper"]');
        $(this).closest('tr').addClass('hidden');
        $(e_wrapper).find('*[data-action="add_person"]').removeClass('hidden');
    })


    $('*[data-action="store_person"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function(e) {

        var o_ajax_params = {};

        o_ajax_params['b_ajax'] = 1;

        $(this).closest('form').find('input').each(function(nl_ix, e_input) {
            o_ajax_params[$(e_input).attr('name')] = $(e_input).val();
        })

        $.ajax({
            type: "GET",
            data: o_ajax_params,
            url: '/meeting/store-person',
            cache: false,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data) {

                if(data.hasOwnProperty('s_content')) {
                    console.log(data);
                    $(s_ajax_win).find('*[data-target="content"]').html(data.s_content);
                } else {
                    console.log(data);
                }

                /*
                var e_wrapper = $(this).closest('*[data-type="wrapper"]');
                $(this).closest('tr').addClass('hidden');
                $(e_wrapper).find('*[data-action="add_person"]').removeClass('hidden');
                */

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax-win: there was an error', textStatus, errorThrown);
            }
        });
    })


</script>
