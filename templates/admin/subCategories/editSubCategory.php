<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

<h1><?php echo $results['pageTitle']?></h1>
<?php //var_dump($results)?>
<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="id" value="<?php echo $results['subCategory']->id ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

            <ul>

              <li>
                <label for="name">Name</label>
                <textarea name="name" id="name" placeholder="Name" required maxlength="100000" style="height: 2em;"><?php echo htmlspecialchars( $results['subCategory']->name )?></textarea>
              </li>

              <li>
                <label for="category_id">category_id</label>
                <textarea name="category_id" id="category_id" placeholder="Category id" required maxlength="100000" style="height: 2em;"><?php
                    
                    echo htmlspecialchars($results['subCategory']->category_id)
                ?></textarea>
              </li>
              <li>
                <label for="category_name">category_name</label>
                <textarea name="category_name" id="category_name" placeholder="Category name" required maxlength="100000" style="height: 4em;"><?php
                    $categoryName = $results['subCategory']->getCategoryNameBySubCategoryId($results['subCategory']->category_id);
                    echo $categoryName;?>
                </textarea>
              </li>
              <li>
                <label for="categoryName">categoryName</label>
                <select name="categoryName">
                  <option value="0"<?php echo !$results['categories'][0]->id ? " selected" : ""?>>(none)</option>
                <?php foreach ($results['categories'] as $category) { ?>
                  <option value="<?php echo $category->id?>"
                      <?php echo ($category->id == $results['categories'][0]->id) ? " selected" : ""?>><?php echo htmlspecialchars($category->name)?>
                  </option>
                <?php } ?>
                </select>
              </li>
            </ul>

            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>

        </form>

    <?php if ($results['subCategory']->id) { ?>
          <p><a href="admin.php?action=deleteSubCategory&amp;id=<?php echo $results['subCategory']->id ?>" onclick="return confirm('Delete This subCategory?')">
                  Delete This subCategory
              </a>
          </p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>