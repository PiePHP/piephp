var Generator = {
	fieldTypes: [
		'',
		'Formatting',
		{ type: 'Head', label: 'Head' },
		{ type: 'Sub Head', label: 'Sub Head' },
		'',
		'Field Clusters',
		{ type: 'Address', label: 'Address', name: '(NoPrefix)', width: '100%' },
		{ type: 'Address (US)', label: 'Address', name: '(NoPrefix)', width: '100%' },
		{ type: 'Credit Card', label: 'Card', name: '(NoPrefix)', width: '100%' },
		{ type: 'Date Range', label: 'Date Range', name: '(NoPrefix)' },
		{ type: 'First/Last Name', label: 'Name', name: '(NoPrefix)', width: '100%' },
		{ type: 'Full Name', label: 'Name', name: '(NoPrefix)', width: '100%' },
		{ type: 'Name/Company', label: 'Name', name: '(NoPrefix)', width: '100%' },
		{ type: 'Phones', label: 'Phone Numbers', name: '(NoPrefix)', width: '100%' },
		{ type: 'Phones (US)', label: 'Phone Numbers', name: '(NoPrefix)', width: '100%' },
		'',
		'Single Fields',
		{ type: 'Card Number', label: 'Card Number', name: 'CardNumber', length: '20', width: '160px' },
		{ type: 'Card Expiration', label: 'Card Expiration', name: 'CardExpiration', length: '2' },
		{ type: 'Checkbox', label: 'Is Active', name: 'IsActive', length: '1' },
		{ type: 'Checkboxes', label: 'Options', name: 'CheckOptions', length: '64' },
		{ type: 'Country', label: 'Country', name: 'Country', length: '2' },
		{ type: 'Date', label: 'Date', name: 'Date', length: '8' },
		{ type: 'Email', label: 'Email', name: 'Email', length: '64', width: '280px' },
		{ type: 'File', label: 'File', name: 'File', length: '64', width: '360px' },
		{ type: 'Float', label: 'Number', name: 'FloatNumber', length: '4', width: '80px' },
		{ type: 'HTML', label: 'Content', name: 'Content', width: '100%' },
		{ type: 'Image', label: 'Image', name: 'Image', length: '64', width: '360px' },
		{ type: 'Integer', label: 'Number', name: 'IntegerNumber', length: '4', width: '40px' },
		{ type: 'Money', label: 'Amount', name: 'Amount', width: '100px' },
		{ type: 'Password', label: 'Password', name: 'Password', length: '16', width: '100px' },
		{ type: 'Password (Secure)', label: 'Password', name: 'Password', length: '16', width: '100px' },
		{ type: 'Phone', label: 'Phone', name: 'Phone', length: '24', width: '160px' },
		{ type: 'Phone (US)', label: 'Phone', name: 'Phone', length: '24', width: '160px' },
		{ type: 'Radio Options', label: 'Options', name: 'RadioOptions', length: '5' },
		{ type: 'Select Multiple', label: 'Selections', name: 'Selections' },
		{ type: 'Select One', label: 'Selection', name: 'Selection', length: '5' },
		{ type: 'State', label: 'State', name: 'State', length: '2' },
		{ type: 'Text', label: 'Name', name: 'Name', length: '64', width: '240px' },
		{ type: 'Text Area', label: 'Description', name: 'Description', width: '100%' },
		{ type: 'URL', label: 'URL', name: 'URL', length: '64', width: '100%' },
		{ type: 'Username', label: 'Username', name: 'Username', length: '16', width: '120px' },
		{ type: 'Yes/No', label: 'YesNo', name: 'YesNo', length: '1' },
		{ type: 'Zip', label: 'Zip', name: 'Zip', length: '10', width: '120px' },
		'',
		'Relation Types',
		{ type: 'Parent Record', label: 'Parent', name: 'Parent', length: '4', controlType: 'Select' },
		{ type: 'Child Records', label: 'Children', name: 'Children', length: '4' }
	],
	
	start: function() {
		
	}

};

$(Generator.start);