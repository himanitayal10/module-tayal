# Magento / PHP Technical Excercise

# The Requirement

We want you to write code that supports importing customers and their addresses.
The requirement is to import from a sample CSV or JSON at present however the
code should be written to support importing from other sources in future. We've
intentionally used a slightly ambiguous term of "profiles" to allow for future
scope e.g. two CSVs but with differing columns, hence two profiles.

The two sample files which you need to accomodate are provided.

The user interface to the code should be via a CLI command as below:

`bin/magento customer:import <profile-name> <source>`

So to import from the CSV and the JSON respectively the user would execute
either one of the following (you can vary the command format slightly if you
wish):

```
bin/magento customer:import sample-csv sample.csv
bin/magento customer:import sample-json sample.json
```

Note that the import doesn't need to handle addresses, as there are none in the
import file. Also, the import should assume customers are imported to the
default website in the general customer group (as the import files don't have
these data elements in).

The code should be written to support the latest version of Magento and a
supported version of PHP. You should tell us what versions you used.

We are looking for:

- Neat and concise code that is well-written and easy to read
- Good architecture which supports the SOLID principles
- Specifically, the ability to add support for additional profiles via extension
  and not modification
- Adherence to the latest Magento standards
- Code that is your own (e.g. no copy-pasting), though the correct usage of
  libraries is encouraged via composer (any libraries used must be publically
  available)
- Your code should be unit tested as per good Engineering Practice

Note that the code is only expected to work on a vanilla Magento instance which
has no customer data present. We expect the file to run to the end without any
unhandled errors.

We're not looking for error checking as such, but if you wish to add some that's
fine.

# Guidance

You can take as much time as you wish on the solution, however we would expect
the excercise to take about 5 hours.

# Submission

When complete please zip your solution (just your code please, don't send any
Magento code) and send it to us. Please tell us which Magento and PHP versions
you used. Please explain any additional steps (other than shown below) that we
need to take to use your module, and why.

Please ensure you have a `composer.json` file present in your module(s) and that
this specifies a version, as we will use `composer require` (shown below) to
install your module.

We will assess your code in production mode in a vanilla Magento installation,
as such you should ensure that `setup:di:compile` runs successfully before
making your submission. For clarity, the demo data will not be present.

When assessing your code we will:

- Install the latest version of Magento with a supporting PHP version
- `composer require` your package via a path repository
- Run `module:enable` for your module(s)
- Run `di:compile` and `setup:static-content:deploy`
- Run the two import commands, which should succeed

We will only assess the quality of the code if the above steps are successful.
