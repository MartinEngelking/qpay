var LIST_URL = '/api/v1/transactions'; // TODO: do not hardcode this here; get it automatically from the named route

function TransactionList($table) {
    this.first_load = true;
    this.columns = null;
    this.$table = $table;
    this.$tbody = $table.find('tbody');
}

/**
 * Adds an array of transactions to the list.
 * @param transactions
 */
TransactionList.prototype.addTransactions = function(transactions) {
    var self = this;
    transactions.forEach(function(transaction) {
        self.addTransaction(transaction);
    })
};

/**
 * Adds a single transaction to the list.
 * @param transaction
 */
TransactionList.prototype.addTransaction = function(transaction) {
    this.grabHeader(transaction);
    var getTransactionColumn = function(column) {
        return transaction[column];
    };

    this.$tbody.prepend(
        '<tr><td>' + this.columns.map(getTransactionColumn).join('</td><td>') + '</td></tr>'
    )
};

/**
 * This grabs column names the first time we see data.
 * @param transaction
 */
TransactionList.prototype.grabHeader = function (transaction) {
    if(this.columns) {
        return;
    }
    this.columns = [];

    for(var column in transaction) {
        if(!transaction.hasOwnProperty(column)) {
            continue;
        }
        this.columns.push(column);
    }

    this.renderHeader();
};

/**
 * Renders the table header
 */
TransactionList.prototype.renderHeader = function() {
    var $thead = this.$table.find('thead');
    console.log(this.columns);
    $thead.html(
        '<tr><th>' + this.columns.join('</th><th>') + '</th></tr>'
    );
};

/**
 * Initial fetch of transactions
 * @returns {*}
 */
function fetchTransactions() {
    return $.get(LIST_URL).then( function(response) {
        var transactions = response.transactions;
        list.addTransactions(transactions);
        return transactions;
    });
}
var list;
$(function() {
    list = new TransactionList($('table#transactionList'));
    fetchTransactions();
});