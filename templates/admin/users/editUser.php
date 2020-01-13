<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

<h1><?php echo $results['pageTitle']?></h1>

<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="id" value="<?php echo $results['user']->id ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

            <ul>

              <li>
                <label for="username">Username</label>
                <textarea name="username" id="username" placeholder="Your username" required maxlength="100000" style="height: 2em;"><?php echo htmlspecialchars( $results['user']->username )?></textarea>
              </li>

              <li>
                <label for="password">Password</label>
                <textarea name="password" id="password" placeholder="Your password" required maxlength="100000" style="height: 2em;"><?php echo htmlspecialchars( $results['user']->password )?></textarea>
              </li>

              <li>
                <label for="activityStatus">Status</label>
                <input type="checkbox" name="activityStatus" <?php if ($results['user']->activityStatus === 1) echo 'checked'?>>
              </li>

            </ul>

            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>

        </form>

    <?php if ($results['user']->id) { ?>
          <p><a href="admin.php?action=deleteUser&amp;id=<?php echo $results['user']->id ?>" onclick="return confirm('Delete This user?')">
                  Delete This user
              </a>
          </p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>