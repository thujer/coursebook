<?php

namespace Runtime\App\Template;

use Runtime\request;

$o_meeting = $this->a_template['o_result'];
$a_person = $this->a_template['a_person'];
$a_person_standin = $this->a_template['a_person_standin'];
$a_person_unconfirmed = $this->a_template['a_person_unconfirmed'];
?>
<div class="container">
    <div class="page-header">
        <h1 title="ID:<?= $o_meeting->nl_id_meeting; ?>"><?= $o_meeting->s_name; ?></h1>
        <p>Datum konání: <?= $o_meeting->dt_when; ?></p>
        <p><?= $o_meeting->s_detail; ?></p>
    </div>

    <form>
        <div class="row">
            <div class="col-12-md">
                <h2>Účastníci</h2>
                <p class="alert alert-success">Maximální počet lidí: <?= $o_meeting->nl_people_max; ?>.
                    <br/>
                    Účast těchto zájemců je potvrzena. Pokud jste si účast rozmysleli, odeberte se ze seznamu
                    zadáním svého kódu a potvrzením tlačítkem Odebrat (pole zobrazíte kliknutím na symbol oka).
                    Pokud heslo neznáte, nechte jej prázdné a klikněte na tlačítko Odebrat, bude Vám zasláno.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="">

            </div>
            <table class="table table-hover" data-type="wrapper">
                <?php
                if (is_array($a_person) && count($a_person)) {
                    ?>
                    <tr>
                        <th>Číslo</th>
                        <th>Jméno a příjmení</th>
                        <th>Akce</th>
                    </tr>
                    <?php
                    $nl_ix = 0;
                    foreach ($a_person as $o_person) {
                        ?>
                        <tr data-nl_id_person="<?= $o_person->nl_id_person; ?>" data-nl_id_meeting="<?= request::get_var('nl_id_meeting'); ?>">
                            <td><?= ++$nl_ix; ?></td>
                            <td><?= $o_person->s_name; ?></td>
                            <td data-parent="show-toggle">
                                <i data-action="show-toggle" class="glyphicon glyphicon-eye-open"></i>
                            <span data-target="show-toggle" class="hidden">
                                <input name="s_pin" value="" placeholder="Vaše heslo"/>
                                <span data-action="remove_meeting_person"><i class="glyphicon glyphicon-remove"></i> Odebrat</span>
                            </span>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5">Seznam je prázdný</td>
                    </tr>
                    <?php
                }
                ?>
                <tr class="hidden" data-target="add_person">
                    <td colspan="3">
                        <input type="hidden" placeholder="" name="nl_id_meeting" value="<?= $o_meeting->nl_id_meeting; ?>" required="required"/>
                        <input type="hidden" placeholder="" name="nl_id_person" value="0" required="required"/>
                        <input type="text" data-name="Jméno" placeholder="Jméno a příjmení" name="s_name" required="required"/>
                        <input type="phone" id="phone" data-name="Telefon" placeholder="Váš telefon (9 číslic)" name="s_phone" required="required"/>
                        <input type="email" data-name="E-mail" placeholder="Váš e-mail" name="s_email"/>
                        <input type="text" data-name="Poznámka" placeholder="Poznámka" name="s_note"/>
                    <span class="panel">
                        <span data-action="store_person"><i class="glyphicon glyphicon-ok"></i> Uložit</span> |
                        <span data-action="remove_person"><i class="glyphicon glyphicon-remove"></i> Zrušit</span>
                    </span>
                        <div class="message"></div>
                    </td>
                </tr>
                <?php
                if (count($a_person) < $o_meeting->nl_people_max) {
                    ?>
                    <tr data-action="add_person">
                        <td colspan="6"><i class="glyphicon glyphicon-plus-sign"></i> Přidat se</td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <?php
            if (count($a_person) >= $o_meeting->nl_people_max) {
                ?>
                <p>Dosaženo maximálního počtu účastníků, pokud máš zájem, můžeš se přidat jako náhradník. V případě uvolnění místa budeš informován e-mailem.</p>
                <?php
            }
            ?>
        </div>
    </form>


    <?php
    //echo count($a_person) . ' vs ' . $o_meeting->nl_people_max;
    if (count($a_person) >= $o_meeting->nl_people_max) {
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

                        <tr data-nl_id_person="<?= $o_person->nl_id_person; ?>" data-nl_id_meeting="<?= request::get_var('nl_id_meeting'); ?>">
                            <td><?= ++$nl_ix; ?></td>
                            <td><?= $o_person->s_name; ?></td>
                            <td data-parent="show-toggle">
                                <i data-action="show-toggle" class="glyphicon glyphicon-eye-open"></i>
                            <span data-target="show-toggle" class="hidden">
                                <input name="s_pin" value="" placeholder="Vaše heslo"/>
                                <span data-action="remove_meeting_person"><i class="glyphicon glyphicon-remove"></i> Odebrat</span>
                            </span>
                            </td>
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
                        <input type="hidden" placeholder="" name="nl_id_meeting" value="<?= $o_meeting->nl_id_meeting; ?>"/>
                        <input type="hidden" placeholder="" name="nl_id_person" value="0"/>
                        <input type="text" placeholder="Jméno a příjmení" name="s_name"/>
                        <input type="phone" placeholder="Váš telefon" name="s_phone"/>
                        <input type="text" placeholder="Váš e-mail" name="s_email"/>
                        <input type="text" placeholder="Poznámka" name="s_note"/>

                        <span class="panel" data-parent="show-toggle">
                            <i data-action="show-toggle" class="glyphicon glyphicon-eye-open"></i>
                            <span data-target="show-toggle" class="hidden">
                                <span data-action="store_person"><i class="glyphicon glyphicon-ok"></i> Uložit</span> |
                                <span data-action="remove_person"><i class="glyphicon glyphicon-remove"></i> Zrušit</span>
                            </span>
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


    if (is_array($a_person_unconfirmed) && count($a_person_unconfirmed)) {
        ?>

        <!--<form>-->
        <h2>Nepotvrzení zájemci</h2>
        <p class="alert alert-warning">Účast je nutné potvrdit zadáním svého kódu. Pole se objeví po kliknutí na symbol oka (níže). Pokud heslo neznáte, nebo jste jej zapoměli, nechte jej prázdné a
            bude Vám zasláno na e-mail nebo SMS dle tlačítka. Pokud jste si účast rozmysleli, odeberete se ze seznamu stejným způsobem tlačítkem Odebrat</p>
        <table class="table table-hover" data-type="wrapper">
            <?php
            //dbg($a_person_unconfirmed);

            ?>
            <tr>
                <th>Číslo</th>
                <th>Jméno a příjmení</th>
                <th>Akce</th>
            </tr>
            <?php
            foreach ($a_person_unconfirmed as $o_person) {
                ?>
                <tr data-nl_id_person="<?= $o_person->nl_id_person; ?>" data-nl_id_meeting="<?= request::get_var('nl_id_meeting'); ?>">
                    <td><?= ++$nl_ix; ?></td>
                    <td><?= $o_person->s_name; ?></td>
                    <td data-parent="show-toggle">
                        <i data-action="show-toggle" class="glyphicon glyphicon-eye-open"></i>
                        <span data-target="show-toggle" class="hidden">
                            <input name="s_pin" value="" placeholder="Vaše heslo"/>
                            <span data-action="confirm-by-sms"><i class="glyphicon glyphicon-envelope"></i> Potvrdit SMS</span> |
                            <span data-action="confirm-by-email"><i class="glyphicon glyphicon-envelope"></i> Potvrdit E-mailem</span> |
                            <span data-action="remove_meeting_person"><i class="glyphicon glyphicon-remove"></i> Odebrat</span>
                        </span>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <!--</form>-->
        <?php
    } else {
        ?>
        <?php
    }
    ?>

    <div class="" data-target="s_content"></div>
    <div class="" data-target="s_error"></div>
    <div class="" data-target="s_message"></div>
    <hr/>
    <a href="/meeting/list" data-id="load_all">Zpět na přehled setkání</a>

</div>

<script type="text/javascript">

    $(function () {
        $('input[type="phone"]').intlTelInput({
            initialCountry: 'cz'
        });
    });


    $('*[data-id="load_all"]').click(function (e) {
        $.ajax({
            url: '/meeting/list',
            data: {
                b_ajax: true
            },
            success: function (response, status) {
                $('*[data-id="content"]').html(response);
            }
        });
        e.preventDefault();
    });


    $('*[data-id="load_person"]').click(function (e) {
        var id_child = $(this).attr('data-item');
        $.ajax({
            url: '/person/detail',
            data: {
                b_ajax: true,
                id_child: id_child
            },
            success: function (response, status) {
                $('*[data-id="content"]').html(response);
            }
        });
        e.preventDefault();
    });


    $('*[data-action="add_person"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function (e) {
        var e_wrapper = $(this).closest('*[data-type="wrapper"]');
        $(e_wrapper).find('*[data-target="add_person"]').removeClass('hidden');
        $(e_wrapper).find('*[data-action="add_person"]').addClass('hidden');
    });


    $('*[data-action="remove_person"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function (e) {
        var e_wrapper = $(this).closest('*[data-type="wrapper"]');
        $(this).closest('tr').addClass('hidden');
        $(e_wrapper).find('*[data-action="add_person"]').removeClass('hidden');
    })


    $('*[data-action="store_person"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function (e) {

        var o_ajax_params = {};

        o_ajax_params['b_ajax'] = 1;
        //o_ajax_params['nl_id_meeting'] = ;

        var b_error = false;
        var $message = $(this).closest('form').find('.message');

        $message.html('');

        $(this).closest('form').find('input').each(function (nl_ix, e_input) {

            var s_item_name = $(e_input).attr('name');
            var s_item_value = $(e_input).val();
            var s_item_required = $(e_input).attr('required');
            var s_item_type = $(e_input).attr('type');
            var s_item_title = $(e_input).attr('data-name');

            if ((s_item_required) && (s_item_value == '')) {
                $(e_input).addClass('error');
                $message.append(s_item_title + ' není vyplněno<br >').css('color', 'red');
                b_error = true;
            }

            if (s_item_type == 'phone') {

                s_item_value = $('#phone').intlTelInput("getNumber");

                if (s_item_value.length < 9) {
                    $(e_input).addClass('error');
                    $message.append('Telefonní číslo je příliš krátké, mělo by mít 9 číslic<br >').css('color', 'red');
                    b_error = true;
                }
            }

            if (!b_error) {
                $(e_input).removeClass('error');
            }

            o_ajax_params[s_item_name] = s_item_value;
        });

        if (!b_error) {
            $message.html('');

            $.ajax({
                type: "GET",
                data: o_ajax_params,
                url: '/meeting/store-person',
                cache: false,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (o_data) {

                    if (o_data.hasOwnProperty('s_content')) {
                        console.log(o_data);
                        $message.html(o_data.s_content);
                    }

                    if (o_data.hasOwnProperty('a_message')) {
                        if (o_data.a_message.length > 0) {
                            o_data.a_message.forEach(function (s_message) {
                                $message.append(s_message + '<br />');
                            });
                        }
                    }

                    if (o_data.hasOwnProperty('a_error')) {
                        if (o_data.a_error.length > 0) {
                            b_error = true;
                            o_data.a_error.forEach(function (s_error) {
                                $message.append('<span style="color: red">' + s_error + '</span><br />');
                            });
                        }
                    }

                    // TODO: dat dopryc :-)
                    if (!b_error) {
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    }

                    /*
                     var e_wrapper = $(this).closest('*[data-type="wrapper"]');
                     $(this).closest('tr').addClass('hidden');
                     $(e_wrapper).find('*[data-action="add_person"]').removeClass('hidden');
                     */

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Ajax-win: there was an error', textStatus, errorThrown);
                }
            });
        }
    });


    $('*[data-action="confirm-by-sms"],*[data-action="confirm-by-email"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function (e) {

        var o_ajax_params = {};

        var nl_id_person = $(this).closest('*[data-nl_id_person]').attr('data-nl_id_person');
        var nl_id_meeting = $('input[name="nl_id_meeting"]').val();
        var s_pin = $(this).closest('*[data-nl_id_person]').find('input[name="s_pin"]').val();

        $(this).closest('*[data-nl_id_person]').find('input[name="s_pin"]').removeClass('hidden');

        o_ajax_params = {
            b_ajax: 1,
            nl_id_meeting: nl_id_meeting,
            nl_id_person: nl_id_person,
            s_pin: s_pin
        }

        $(this).closest('form').find('input').each(function (nl_ix, e_input) {
            o_ajax_params[$(e_input).attr('name')] = $(e_input).val();
        })

        $.ajax({
            type: "GET",
            data: o_ajax_params,
            url: '/meeting/' + $(this).attr('data-action'),
            cache: false,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (o_data) {

                if (o_data.hasOwnProperty('s_content')) {
                    $('*[data-target="s_content"]').html(o_data.s_content);
                }

                if (o_data.hasOwnProperty('a_message')) {
                    if (o_data.a_message !== null) {
                        o_data.a_message.forEach(function (s_message) {
                            $('*[data-target="s_content"]').append(s_message + '<br />');
                        });
                    }
                }

                if (o_data.hasOwnProperty('a_error')) {
                    if (o_data.a_error !== null) {
                        o_data.a_error.forEach(function (s_error) {
                            $('*[data-target="s_content"]').append('<span style="color: red">' + s_error + '</span><br />');
                        });
                    }
                }

                if (s_pin) {
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Ajax-win: there was an error', textStatus, errorThrown);
            }
        });
    })


    $('*[data-action="remove_meeting_person"]').css({
        cursor: 'pointer',
        textDecoration: 'underline'
    }).on('click', function (e) {

        var o_ajax_params = {};

        var nl_id_person = $(this).closest('*[data-nl_id_person]').attr('data-nl_id_person');
        var nl_id_meeting = $('input[name="nl_id_meeting"]').val();
        var s_pin = $(this).closest('*[data-nl_id_person]').find('input[name="s_pin"]').val();

        //$(this).closest('*[data-nl_id_person]').find('input[name="s_pin"]').removeClass('hidden');

        o_ajax_params = {
            b_ajax: 1,
            nl_id_meeting: nl_id_meeting,
            nl_id_person: nl_id_person,
            s_pin: s_pin
        }

        /*$(this).closest('form').find('input').each(function(nl_ix, e_input) {
         o_ajax_params[$(e_input).attr('name')] = $(e_input).val();
         })*/

        $.ajax({
            type: "GET",
            data: o_ajax_params,
            url: '/meeting/remove-meeting-person',
            cache: false,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (o_data) {

                if (o_data.hasOwnProperty('s_content')) {
                    console.log(o_data);
                    $('*[data-target="s_content"]').html(o_data.s_content);
                }

                if (o_data.hasOwnProperty('a_message')) {
                    if (o_data.a_message !== null) {
                        o_data.a_message.forEach(function (s_message) {
                            $('*[data-target="s_content"]').append(s_message + '<br />');
                        });
                    }
                }

                if (o_data.hasOwnProperty('a_error')) {
                    if (o_data.a_error !== null) {
                        o_data.a_error.forEach(function (s_error) {
                            $('*[data-target="s_content"]').append('<span style="color: red">' + s_error + '</span><br />');
                        });
                    }
                }

                if (s_pin) {
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Ajax-win: there was an error', textStatus, errorThrown);
            }
        });
    })

    $('*[data-action="show-toggle"]').css({
        cursor: 'pointer'
    }).on('click', function (e) {

        $target = $(e.target).closest('*[data-parent="show-toggle"]').find('*[data-target="show-toggle"]');

        if ($(this).hasClass('glyphicon-eye-open')) {
            $(this).removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
            $target.removeClass('hidden');
        } else {
            $(this).removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
            $target.addClass('hidden');
        }


    })

</script>
