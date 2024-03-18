## Loan Return Reminder (ID: {{ $loanDetails['id'] }})

Dear {{ $loanDetails['user']['name'] }},

This is a friendly reminder that your loan (ID: {{ $loanDetails['id'] }}) is due for return tomorrow, on {{ Carbon::parse($loanDetails['delivery_date'])->addDay()->format('Y-m-d') }}.

Please ensure you return the loaned items to us by the end of the day tomorrow.  

**Here are some ways to return your loan:**

* **Bring it back to our store:** You can return the loaned items in person to our store located at [Store Address]. Our store hours are [Store Hours].
* **Arrange for pickup (if available):** If pickup is available in your area, you can contact us to schedule a convenient time for pickup.

**For any questions or concerns regarding your loan return, please don't hesitate to contact us:**

* **Email:** [Your Email Address]
* **Phone:** [Your Phone Number]

Thank you for your business!

Sincerely,

The [Your Company Name] Teamp