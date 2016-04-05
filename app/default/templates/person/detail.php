<?php

    namespace Runtime\App\Template;

    $o_child = $this->a_template['o_child'];
    $a_hobby_group = $this->a_template['a_hobby_group'];
    $s_age_caption = $this->a_template['s_age_caption'];
?>

<div class="page-header">
    <h1><?=$o_child->s_name;?> <?=$o_child->s_lastname;?> (<?=$o_child->e_gender;?>, <?=$o_child->nl_age;?> <?=$s_age_caption;?>)</h1>
</div>

<h2>Zájmy</h2>
<table class="table table-responsive table-hover">
    <thead>
        <tr>
            <th class="width-sm">Číslo</th>
            <th class="width-sm">Ikona</th>
            <th>Název</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if(is_array($a_hobby_group) && count($a_hobby_group)) {
        foreach($a_hobby_group as $o_hobby_group) {
            ?>
            <tr>
                <td><?=$o_hobby_group->id_hobby_group;?></td>
                <td><span class="glyphicon <?=$o_hobby_group->s_glyphicon;?>"></td>
                <td><?=$o_hobby_group->s_name;?></td>
                <td></td>
            </tr>
        <?php
        }
    } else {
        ?><tr><td colspan="4">Nebyla nalezena žádná oblast zájmu</td></tr><?php
    }
    ?>
    </tbody>
</table>

<hr />

<h2>Osoby s podobnými zájmy</h2>
<div data-id="classmate">
    <a href="#" data-id="load_classmate">Vyhledat osoby s podobnými zájmy</a>
</div>
<hr />
<a href="/person/list" data-id="load_all">Zobrazit přehled zájemců</a>

<script type="text/javascript">

    $('*[data-id="load_classmate"]').click(function(e) {
        $.ajax( {
            url: '/person/getclassmate',
            data: {
                b_ajax: true,
                child_id: parseInt('<?=$o_child->nl_id_person;?>'),
            },
            success: function(response, status) {
                $('*[data-id="classmate"]').html(response);
            }
        });
        e.preventDefault();
    });

    $('*[data-id="load_all"]').click(function(e) {
        $.ajax( {
            url: '/person/list',
            data: {
                b_ajax: true
            },
            success: function(response, status) {
                $('*[data-id="content"]').html(response);
            }
        });
        e.preventDefault();
    })

</script>
