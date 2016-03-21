<?php

    namespace Runtime\App\Template;

    $a_classmate = $this->a_template['a_classmate'];
?>

<table class="table table-responsive table-hover">
    <?php
    if(is_array($a_classmate) && count($a_classmate)) {
        ?>
        <thead>
            <tr>
                <th class="width-sm">Číslo</th>
                <th class="width-sm">Spolužák</th>
                <th>Zájmové kroužky</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach($a_classmate as $o_classmate) {
            ?>
            <tr>
                <td><?=$o_classmate->id_person;?></td>
                <td><a href="/person/detail?id_child=<?=$o_classmate->id_person;?>"><?=$o_classmate->s_classmate_name;?></a></td>
                <td><?=$o_classmate->s_hobby_group_name;?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <?php
    } else {
        ?>
        <tr>
            <td>Nebyli nalezeni žádné osoby ze stejných setkání</td>
        </tr>
        <?php
    }
    ?>
</table>
