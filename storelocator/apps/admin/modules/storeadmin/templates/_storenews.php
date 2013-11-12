<?php use_helper('Text'); ?>
<script>
  var refreshUrl = '<?php echo url_for('@refreshNews?slug='.$store->getSlug()); ?>';
  var uploadUrl = '<?php echo url_for('storeadmin/uploadImage'); ?>';
</script>
<a class="add-new" href="#addNews">add new</a>

<form method="post" id="formNews" action="<?php echo url_for('storeadmin/newsDel'); ?>">
  <input type="hidden" name="id" id="id" value="">
  <fieldset>

    <?php foreach ($news as $n): ?>

      <div class="group">
        <?php /* ?><input type="checkbox" id="title" name="news[]" value="1"><?php */?>
        <label><?php echo truncate_text( strip_tags( $n->getParagraph(ESC_RAW)), 60, '...');?></label>
        <a href="#news-<?php echo $n->getId();?>" class="sprite-edit-delete delete"><span>delete</span></a>
        <a href="#news-<?php echo $n->getId();?>" class="sprite-edit-delete edit"><span>edit</span></a>
      </div>

    <?php endforeach; ?>

  </fieldset>

</form>

<div class="text-editor">
  <?php foreach ($news as $n): ?>
    <form id="news-<?php echo $n->getId();?>" class="editor-modal" method="post" action="<?php echo url_for('storeadmin/newsMod'); ?>">
      <input type="hidden" name="id" value="<?php echo $n->getId();?>">
      <h3>Diesel - News</h3>
      <div class="editor-para">
        <h4>Paragraph</h4>
        <textarea name="paragrafo" style="width: 668px !important;"><?php echo $n->getParagraph();?></textarea>
      </div>
      <input type="hidden" name="slug" value="<?php echo $n->getSlug() ?>"/>
      <button type="button" id="saveNews-<?php echo $n->getId();?>" class="saveNews" name="save" title="save">save</button>

      <div id="resultNews-<?php echo $n->getId();?>"><p></p></div>
    </form>
  <?php endforeach; ?>
	
  <form id="addNews" class="editor-modal" method="post" action="<?php echo url_for('storeadmin/newsAdd'); ?>">
    <h3>Diesel - News</h3>

    <div class="editor-para">
      <h4>Paragraph</h4>
      <textarea name="paragrafo" style="width: 668px !important;"></textarea>
    </div>
    <input type="hidden" name="slug" id="slug" value="<?php echo $store->getSlug(); ?>"/>
    <button type="button" id="saveNewNews" name="save" title="save">save</button>

    <div id="resultNews"><p></p></div>

  </form>
	
</div>