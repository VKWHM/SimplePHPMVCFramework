 <h2 class="text-center mt-3">Login Page</h2>
 <form action="" method="POST">
    <?php foreach ($form->fields() as $field) { ?>
      <div class="mb-3">
          <?php $field->fieldLabel('form-label') ?>
          <?php $field->fieldInput('form-control') ?>
      </div>
    <?php } ?>
  <button type="submit" class="btn btn-primary">login</button>
 </form>
