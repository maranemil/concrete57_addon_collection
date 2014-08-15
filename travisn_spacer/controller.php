<?php

namespace Concrete\Package\TravisnSpacer;
use Package;
use BlockType;
use SinglePage;
use Loader;
use Events;
use User;
use Group;
use Concrete\Core\Html\Service\Html;
use View;

/*
 * tnSpacer Package written by Tavis Nickels (c) 2009.
 *
 * This code is released under the MIT Licence.
 *
 * THE SOFTWARE IS PROVIDED AS IS, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 * See licence files for details
 *
 */

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'travisn_spacer';
	protected $appVersionRequired = '5.2.0';
	protected $pkgVersion = '1.3';
	
	public function getPackageDescription() {
		return t("Add spacers to your webpage without editing code.");
	}
	
	public function getPackageName() {
		return t("tnSpacer");
	}
	
	public function install() {
		$pkg = parent::install();
		// install block		
		BlockType::installBlockTypeFromPackage('travisn_spacer', $pkg);
	}

}