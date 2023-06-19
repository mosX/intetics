<div class="container">
    <form method='POST' action='/form/'>
        <p>
            <label>Some Default Value</label>
            <textarea name="value"></textarea>
        </p>
        <input type="hidden" name="_csrf_token" value="<?=$csrf?>">
        <input type="submit" value="Submit">        
    </form>
</div>