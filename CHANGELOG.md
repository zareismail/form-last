Version 1.1.2
----------------- 

### Added
 
* Added FormNotFond Exception for not existence child's.

### Fixed
 
* Retrieving input by dot notation.
* Improve the name prefix.

 
### Changed

* Appending rows into field stack by real name without prefix.
* Appending rows into rendered stack by real name without prefix.
* Removed form instance parameters from merge callback method.
* Passing rendered row into internal rendering event.

 
Version 1.1.1
----------------- 

### Added
 
* Added the following methods to `FormBuilder`:
  * `name()`
  * `getName()`
  * `setName()`  
  * `getPrefix()`
  * `setPrefix()` 
  * `getModel()`
  * `setModel()` 
  * `getParent()`
  * `setParent()`
  * `getChild()`
  * `setChild()` 

### Fixed
 
* Filtering rendered rows at retrieving all rows

 
### Changed

* Save child forms with names instead of prefixes
* Used the `parent()`, `prefix()`, `model()`, `child()`, `name()` method's for the alias of setter methods

 