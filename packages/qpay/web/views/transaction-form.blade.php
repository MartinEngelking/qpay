<form role="form" id="transactionForm" action="{{route("store-transaction")}}">
    <div class="form-group">
        <label for="name">Amount:</label> <input class="form-control" class="form-control" type="text" size="7"
                                                 name="amount" id="amount" placeholder="59.97"/>
    </div>

    <div class="form-group">
        <label for="merchant">Business:</label> <input class="form-control" type="text" size="20"
                                                       name="merchant" id="merchant"
                                                       placeholder="Detroit Beer Co."/>
    </div>

    <div class="form-group">
        <label for="address">Address:</label> <input class="form-control" type="address" size="23"
                                                     name="address" id="address"
                                                     placeholder="1529 Broadway St."/>
    </div>

    <div class="form-group">
        <label for="zip">ZIP:</label> <input class="form-control" type="zip" size="7" name="zip" id="zip"
                                             placeholder="48226"
                                             maxlength="5"/>
    </div>

    <div class="clearfix"></div>

    <div class="form-group">
        <button type="submit" id="submit" class="btn btn-primary btn-lg">Submit Transaction</button>
    </div>
</form>