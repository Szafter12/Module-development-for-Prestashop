<h3 id="mymodComments-content-tab" {if isset($new_comment_posted) && $new_comment_posted != null} data-scroll="true"
    {/if} class="page-product-heading">{l s='Product Comments' mod='my_mod_grades_comments'}</h3>

<div class="rte">
    {foreach from=$comments item=comment}
        <p>
            <strong>Comment #{$comment.id_my_mod_comment}:</strong>
            {$comment.comment}<br />
            <strong>Grade: </strong> {$comment.grade}/5
        </p>
        <br />
    {/foreach}
</div>

<div class="rte">
    {assign var=params value=[
    'module_action' => 'list',
    'id_product'=> $smarty.get.id_product
    ]}
    <a href="{$link->getModuleLink('my_mod_grades_comments', 'comments', $params)}">
        {l s='See all comments' mod='my_mod_grades_comments'}
    </a>
</div>

<div class="rte">
    <form action="" method="POST" id="comment-form">
        {if $enable_grades eq 1}
            <div class="form-group">
                <label for="grade">Grade:</label>
                <div class="row">
                    <div class="col-xs-4">
                        <select id="grade" class="form-control" name="grade">
                            <option value="0">-- Choose --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
            </div>
        {/if}
        {if $enable_comments}
            <div class="form-group">
                <label for="comment">Comment:</label>
                <textarea name="comment" id="comment" class="form-control"></textarea>
            </div>
            <div class="submit">
                <button type="submit" name="mymod_pc_submit_comment" class="button btn btn-default button-medium">
                    <span>Send<i class="iconchevron-right right"></i></span>
                </button>
            </div>
        {/if}
    </form>
</div>