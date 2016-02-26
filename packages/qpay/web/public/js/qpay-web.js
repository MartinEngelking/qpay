var LIST_URL = '/api/v1/transactions'; // TODO: do not hardcode this here; get it automatically from the named route

function TransactionList($table) {
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
    $thead.html(
        '<tr><th>' + this.columns.join('</th><th>') + '</th></tr>'
    );
};


/**
 * Submits the form
 * @param e
 */
function submitForm(e) {
    e.preventDefault();
    clearFieldHighlights();
    clearErrorMsg();
    $.ajax({
        url: $form.attr('action'),
        type: 'post',
        dataType: 'json',
        data: $form.serialize(),
        success: handleSubmission,
        error: handleSubmissionError
    });
}

/**
 * Handles successful form submission
 * @param data
 */
function handleSubmission(data) {
    if(data.transaction) {
        list.addTransaction(data.transaction);
    }
}

/**
 * Handles error submission
 * @param data
 */
function handleSubmissionError(data) {
    var response = data.responseJSON;
    switch(response.status) {
        case "failed_validation":
            highlightFields(response.errors);
            break;
        case "failed_fraud":
            errorMsg("This transaction is potentially fraudulent:\n" + response.errors.join("\n"));
            break;
        default:
            alert("An error occurred:\n\n" + data.responseText);
    }
}

function errorMsg(message) {
    $('#errorMessage').html(message.replace(/\n/g, '<br/>'));
    $('#errorMessage').removeClass('hidden');

}
function highlightFields(fields) {
    fields.map( function(field) {
        $('input#' + field).addClass('error');
    });
}

function clearFieldHighlights() {
    $('input.error').removeClass('error');
}
function clearErrorMsg() {
    $('#errorMessage').html();
    $('#errorMessage').addClass('hidden');
}

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
var list, $form;
$(function() {
    list = new TransactionList($('table#transactionList'));
    fetchTransactions();
    $form = $('form#transactionForm');
    $form.submit(submitForm);
});