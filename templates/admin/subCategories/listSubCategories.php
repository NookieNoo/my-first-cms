<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
    <h1>All SubCategories</h1>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

          <table>
            <tr>
              <th>Name</th>
              <th>Category name</th>
            </tr>

<!--<?php echo "<pre>"; print_r ($results['users'][2]->username); echo "</pre>"; ?> Обращаемся к дате массива $results. Дата = 0 -->
            
    <?php foreach ( $results['subCategories'] as $subCategory) { ?>
           
            <tr onclick="location='admin.php?action=editSubCategory&amp;id=<?php echo $subCategory->id?>'">
              <td>
                <?php echo $subCategory->name?>
              </td>
              <td>
                  <?php 
                    $categoryName = $subCategory->getCategoryNameBySubCategoryId($subCategory->category_id);
                    echo $categoryName;?>
                
              </td>
              
            </tr>

    <?php } ?>

          </table>

          <p><?php echo $results['totalRows']?> subCategor<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>

          <p><a href="admin.php?action=newSubCategory">Add a new SubCategory</a></p>

<?php include "templates/include/footer.php" ?>              