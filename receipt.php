<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier    <jeremie.o@laposte.net>
 * Copyright (C) 2011      Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) 2012      Marcos García       <marcosgdf@gmail.com>
 * Copyright (C) 2012      Andreu Bisquerra    <jove@bisquerra.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

$res=@include("../main.inc.php");
if (! $res) $res=@include("../../main.inc.php");
include_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';

$langs->load("main");
$langs->load('cashdesk');

top_httphead('text/html');

$facid=GETPOST('facid','int');
$object=new Facture($db);
$object->fetch($facid);

?>
<html>
    <head>
    <title><?php echo $langs->trans('PrintTicket') ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo DOL_URL_ROOT;?>/cashdesk/css/ticket.css">
</head>

<body>

<div class="entete">
    <div class="logo">
        <?php print '<img src="'.DOL_URL_ROOT.'/viewimage.php?modulepart=mycompany&amp;file='.urlencode('/thumbs/'.$mysoc->logo_small).'">'; ?>
    </div>
    <div class="infos">
        <p class="address"><?php echo $mysoc->name; ?><br>
        <?php print dol_nl2br(dol_format_address($mysoc)); ?><br>
        </p>

        <p class="date_heure"><?php
        // Recuperation et affichage de la date et de l'heure
        $now = dol_now();
        print dol_print_date($now,'dayhourtext').'<br>';
        print $object->ref;
        ?></p>
    </div>
</div>

<br>

<table class="liste_articles">
    <thead>
	<tr class="titres">
            <th><?php print $langs->trans("Label"); ?></th>
            <th><?php print $langs->trans("Qty"); ?></th>
            <th><?php print $langs->trans("TotalTTC"); ?></th>
	</tr>
    </thead>
    <tbody>
    <?php
    foreach ($object->lines as $line)
    {
    ?>
    <tr>
        <td><?php echo $line->product_label;?></td>
        <td><?php echo $line->qty;?></td>
        <td><?php echo price($line->total_ttc);?></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
</table>

<table class="totaux">
<tr>
    <th class="nowrap"><?php echo $langs->trans("TotalHT");?></th>
    <td class="nowrap"><?php echo price($object->total_ht, 1, '', 1, - 1, - 1, $conf->currency)."\n";?></td>
</tr>
<tr>
    <th class="nowrap"><?php echo $langs->trans("TotalVAT").'</th><td class="nowrap">'.price($object->total_tva, 1, '', 1, - 1, - 1, $conf->currency)."\n";?></td>
</tr>
<tr>
    <th class="nowrap"><?php echo ''.$langs->trans("TotalTTC").'</th><td class="nowrap">'.price($object->total_ttc, 1, '', 1, - 1, - 1, $conf->currency)."\n";?></td>
</tr>
</table>

<script type="text/javascript">
    window.print();
</script>

<a class="lien" href="#" onclick="javascript: window.close(); return(false);"><?php echo $langs->trans("Close"); ?></a>
</body>
</html>
