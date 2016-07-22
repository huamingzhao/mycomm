<!doctype html>
<html>
    <head>
    </head>
    <body>
        <h3>demo default</h3>
        <?php if(isset($_POST['test_default'])){
            var_dump($_POST);
        }
        ?>
        <form method="post">
            <?php echo Editor::factory("默认编辑器，你还可以自定义模板","default",array("field_name"=>"test_default"))?>
            <input type="submit">
        </form>

        <h3>demo simple</h3>
        <?php if(isset($_POST['test_simple'])){
            var_dump($_POST);
        }
        ?>
        <form method="post">
            <?php echo Editor::factory("简易编辑器","simple",array("field_name"=>"test_simple"))?>
            <input type="submit">
        </form>

        <h3>demo nobar</h3>
        <?php if(isset($_POST['test_nobar'])){
            var_dump($_POST);
        }
        ?>
        <form method="post">
            <?php echo Editor::factory("简易编辑器","nobar",array("field_name"=>"test_nobar"))?>
            <input type="submit">
        </form>
    </body>
</html>