<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$data = $params->get('data', '');
$data = json_decode(base64_decode($data));

//$params = 'option=com_login&token=qwerty&task=login&'
$params = '?secure_key=secure_value&option=com_login&task=login&';

$version = new JVersion();
$isJ3    = (version_compare($version->getShortVersion(), '3.0.0') > 0);

if($isJ3) {
    JHtml::_('formbehavior.chosen');
}

$sites = array();
foreach($data as $item) {
    $sites[] = '<option value="' . $item->url . $params . 'token=' . $item->token . '">' .
        $item->sitename .
    '</option>';
}
?>
<link href="<?php echo JURI::base() ?>modules/mod_atingobypass/css/font-awesome.min.css"
      rel="stylesheet">
<span class="atingobypass">
    <i class="fa fa-external-link"></i>
    <select>
        <option value="">Ir a ...</option>
        <?php echo implode('', $sites) ?>
    </select>
</span>
<script type="text/javascript">
<?php if(!$isJ3): ?>
$$('.atingobypass select').forEach(function(elem){
    elem.addEvent('change', function(event) {
        var uri = $(event.target).value;
        if(uri != '') {
            window.open($(event.target).value, '_blank');
        }

        $(event.target).selectedIndex = 0;
    });
});
<?php else: ?>
jQuery('.atingobypass select').chosen().change(function(event) {
    var uri = jQuery(this).val();
    if(uri != '') {
        window.open(uri, '_blank');
    }
});
<?php endif; ?>
</script>