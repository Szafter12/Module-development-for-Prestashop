<fieldset>
    <h2>My Module configuration</h2>
    <div class="panel">
        <div class="panel-heading">
            <legend><img src="../img/admin/cog.gif" alt="" width="16" />Configuration</legend>
        </div>
        <form action="" method="post">
            <div class="form-group clearfix">
                <label class="col-lg-3">Enable grades:</label>
                <div class="col-lg-9">
                    <img src="../img/admin/enabled.gif" alt="" />
                    <input type="radio" id="enable_grades_1" name="enable_grades" value="1"
                        {if isset($enable_grades) && $enable_grades == 1} checked {/if} />
                    <label class="t" for="enable_grades_1">Yes</label>
                    <img src="../img/admin/disabled.gif" alt="" />
                    <input type="radio" id="enable_grades_0" name="enable_grades" value="0"
                        {if isset($enable_grades) && $enable_grades == 0} checked {/if} />
                    <label class="t" for="enable_grades_0">No</label>
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-lg-3">Enable comments:</label>
                <div class="col-lg-9">
                    <img src="../img/admin/enabled.gif" alt="" />
                    <input type="radio" id="enable_comments_1" name="enable_comments" value="1"
                        {if isset($enable_comments) && $enable_comments == 1} checked {/if} />
                    <label class="t" for="enable_comments_1">Yes</label>
                    <img src="../img/admin/disabled.gif" alt="" />
                    <input type="radio" id="enable_comments_0" name="enable_comments" value="0"
                        {if isset($enable_comments) && $enable_comments == 0} checked {/if} />
                    <label class="t" for="enable_comments_0">No</label>
                </div>
            </div>
            <div class="panel-footer">
                <input class="btn btn-default pull-right" type="submit" name="mymod_pc_form" value="Save" />
            </div>
        </form>
    </div>
</fieldset>

{if isset($confiramtion)}
    <div class="alert alert-success">Settings updated</div>
{/if}