 <h2 class="text-center mt-3">Create An Account</h2>
 <form action="" method="POST">
    <?php foreach ($form->fields() as $field) { ?>
      <div class="mb-3">
          <?php $field->fieldLabel('form-label') ?>
          <?php $field->fieldInput('form-control') ?>
      </div>
    <?php } ?>
  <button type="submit" class="btn btn-primary">Register</button>
 </form>
