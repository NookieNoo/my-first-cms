<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
	  
    <h1>All Users</h1>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

          <table>
            <tr>
              <th>Username</th>
              <th>Password</th>
              <th>Status</th>
            </tr>

<!--<?php echo "<pre>"; print_r ($results['users'][2]->username); echo "</pre>"; ?> Обращаемся к дате массива $results. Дата = 0 -->
            
    <?php foreach ( $results['users'] as $user) { ?>

            <tr onclick="location='admin.php?action=editUser&amp;id=<?php echo $user->id?>'">
              <td>
                <?php echo $user->username?>
              </td>
              <td>
                <?php echo $user->password?>
              </td>
              <td>
                  <input type='checkbox' name="activityStatus" disabled <?php if ($user->activityStatus === 1) echo 'checked'?>>
              </td>
            </tr>

    <?php } ?>

          </table>

          <p><?php echo $results['totalRows']?> user<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>

          <p><a href="admin.php?action=newUser">Add a new user</a></p>

<?php include "templates/include/footer.php" ?>              