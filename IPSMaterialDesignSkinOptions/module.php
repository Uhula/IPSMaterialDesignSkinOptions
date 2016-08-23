<?

class IPSMaterialDesignSkinOptions extends IPSModule
{

  public function Create() {
    //Never delete this line!
    parent::Create();
        
    //These lines are parsed on Symcon Startup or Instance creation
    //You cannot use variables here. Just static values.
    $this->RegisterPropertyInteger("SkinTheme", 0);
    $this->RegisterPropertyInteger("AccentTheme", 0);
    $this->RegisterPropertyInteger("WebfrontID", 0);
    $this->RegisterPropertyInteger("LogLevel", 0);
    $this->RegisterPropertyString("Custom1",'{"theme":"Custom1", "icons":"white","colors":{"bc":"803030","bcc":"f24242","bcn1":"400000","bcn2":"812121","bcn3":"812121","bcg":"424242","fc":"FFFFFF","fcn":"FFFFFF","fch":"E0E0E0","fcl":"F0F0F0","fcv":"F0F0F0","ac":"FFB74D"}}');
  }
  
  public function Destroy() {
        
    //Never delete this line!
    parent::Destroy();
  }
  
  
  public function ApplyChanges() {
    //Never delete this line!
    parent::ApplyChanges();
    
    $this->Log("[ApplyChanges]");
    // Mögliche Skin-Werte laden und dem Profil zuweisen
    $themes = $this->GetThemes();
    if ($themes) {
      $arr = [];
      foreach ($themes as $key => $value) {
//        $arr[] = array($key,$value["theme"],"","0x".$value["colors"]["ac"] ); // -1=transparent
        $arr[] = array($key,$value["theme"],"",-1 ); // -1=transparent
      }
      $this->RegisterProfileIntegerAssociation("MDSO.Theme", "", "", "",$arr);
    }
    
    //Variablen erstellen
    $this->RegisterVariableInteger("SkinTheme", "Thema", "MDSO.Theme");
    $this->RegisterVariableInteger("AccentTheme", "Akzent Thema", "MDSO.Theme");
    $this->RegisterVariableInteger("WebfrontID", "WebFront ID");
    $this->EnableAction("SkinTheme");
    $this->EnableAction("AccentTheme");
    
    $this->SetValueInteger("SkinTheme", $this->ReadPropertyInteger("SkinTheme") );
    $this->SetValueInteger("AccentTheme", $this->ReadPropertyInteger("AccentTheme"));
    $this->SetValueInteger("WebfrontID", $this->ReadPropertyInteger("WebfrontID"));
        
        
    $this->Update();
  }
  
  public function Update() {
    $this->Log("[Update]" );
    if ( $this->ReadPropertyInteger("WebfrontID") == 0)
      $this->SetStatus(201);        
    else {
      $this->SetStatus(102);  
      $this->ApplySkin();
      $this->ApplyAccent();
    }  
         
  }

  public function Log($msg) {
    IPS_LogMessage("MDSO", $msg );
  }
  
  public function SetSkinTheme($skintheme, $accenttheme) {
    $this->Log("[SetSkinTheme] Skin:".$skintheme." Accent:".$accenttheme );
    if ( $skintheme != -1 )  
      $this->SetValueInteger("SkinTheme", $skintheme );
    if ( $accenttheme != -1 )
      $this->SetValueInteger("AccentTheme", $accenttheme );
    $this->Update();
  }
  
  /* GetThemes
     --------------------------------------------------------------------  
     Read the skin-presets and add the custom skins, returning them in
     one array     
  */
  private function GetThemes() {
    $presets_json = __DIR__ . "/presets.json";
  
    $presets = file_get_contents( $presets_json );
    $themes = json_decode( $presets, true );
    if (json_last_error() != JSON_ERROR_NONE ) {
      $this->Log("[GetThemes] "."Error read presets ".$presets_json );
      return FALSE;
    }
    
    // Custom-Skins hinzufügen
    $custom = $this->ReadPropertyString("Custom1");
    $themes[200] = json_decode( $custom, true );
    if (json_last_error() != JSON_ERROR_NONE ) {
      $this->Log("[GetThemes] "."Custom skin 1 wrong ".$custom );
      return FALSE;
    }
    return $themes;
  }
  
  /* ApplySkin 
     --------------------------------------------------------------------  
          
  */
  private function ApplySkin() {
    $this->Log("[ApplySkin] ".$this->GetValueInteger("SkinTheme") );
 
    $WebFrontID = $this->ReadPropertyInteger("WebfrontID");
    $SkinTheme = $this->GetValueInteger("SkinTheme");

    $skin_path    = IPS_GetKernelDir()."webfront/user/skins/MaterialDesignLight/";
    $icons_css    = $skin_path."icons.css";
    $icons_css_no = $skin_path."icons.css.no";
    $webfront_css = $skin_path."webfront.css";
    
    $themes = $this->GetThemes();
    if ($themes == FALSE ) {
      return FALSE;
    }
       
    if ( !array_key_exists($SkinTheme, $themes) ) {
      $this->Log("[ApplySkin] "."unknown skin ".$SkinTheme );
      $this->SetStatus(202);
      return FALSE;
    }
    
    $theme = $themes[$SkinTheme];
    // webfront.css Datei patchen
    $css = file_get_contents( $webfront_css );
    foreach ($theme["colors"] as $key => $value) {
      if ($key != "ac")
        $css = preg_replace("=#[0-9A-F]{6}/\*".$key."\*/=i","#".$value."/*".$key."*/",$css);
    }
    file_put_contents( $webfront_css, $css );
         
    // icons.css anpassen
    if ( $theme["icons"] == "white" ) {
      if (file_exists($icons_css))
        rename($icons_css, $icons_css_no);
    } else {
      if (file_exists($icons_css_no))
        rename($icons_css_no, $icons_css);
    }
          
    // WebFront neu laden
    WFC_Reload ( $WebFrontID );
    
  }

  /* ApplyAccent 
     --------------------------------------------------------------------  
          
  */
  private function ApplyAccent() {
    $this->Log("[ApplyAccent] ".$this->GetValueInteger("AccentTheme") );
 
    $WebFrontID = $this->ReadPropertyInteger("WebfrontID");
    $AccentTheme = $this->GetValueInteger("AccentTheme");

    $skin_path    = IPS_GetKernelDir()."webfront/user/skins/MaterialDesignLight/";
    $webfront_css = $skin_path."webfront.css";
    
    $themes = $this->GetThemes();
    if ($themes == FALSE ) {
      return FALSE;
    }
       
    if ( !array_key_exists($AccentTheme, $themes) ) {
      $this->Log("[ApplyAccent] "."unknown skin ".$SkinTheme );
      $this->SetStatus(202);
      return FALSE;
    }
    
    $color = "FFFFFF";
    $theme = $themes[$AccentTheme];
    // webfront.css Datei patchen
    $css = file_get_contents( $webfront_css );
    foreach ($theme["colors"] as $key => $value) {
      if ($key == "ac") {
        $css = preg_replace("=#[0-9A-F]{6}/\*".$key."\*/=i","#".$value."/*".$key."*/",$css);
        $color = $value;
      }
    }
    file_put_contents( $webfront_css, $css );
    
    // Icons einfärben
    $icon_path = $skin_path."icons_colored/";

    if (!file_exists($icon_path) || !is_dir($icon_path) ) {
	    $this->Log("Verzeichnis (".$icon_path.") für die eingefärbten Icons existiert nicht.");
	    return false;
    }
    if (!is_writeable($icon_path)) {
	    $this->Log("Verzeichnis (".$icon_path.") für die eingefärbten Icons ist nicht beschreibbar.");
      return false;
    }

    if ($dh = opendir($icon_path)) {
      while (($file = readdir($dh)) !== false) {
        if (filetype($icon_path.$file)=="file" && pathinfo($file,PATHINFO_EXTENSION)=="svg") {
          $svg = file_get_contents( $icon_path.$file );
          $svg = preg_replace('~stroke="#[0-9A-F]{6}"~i','stroke="#'.$color.'"',$svg);
			    file_put_contents($icon_path.$file, $svg);
        }
      }
      closedir($dh);
    }
         
    // WebFront neu laden
    WFC_Reload ( $WebFrontID );
  
  }
  
  public function RequestAction($Ident, $Value) {
    switch($Ident) {
      case "SkinTheme":
        $this->SetSkinTheme($Value, -1);
        break;
      case "AccentTheme":
        $this->SetSkinTheme(-1, $Value);
        break;
      default:
        throw new Exception("Invalid ident");
        }
  }
  
  private function SetValueInteger($Ident, $value) {
    $id = $this->GetIDForIdent($Ident);
    if (GetValueInteger($id) <> $value) {
      SetValueInteger($id, $value);
      return true;
    }
    return false;
  }
  private function GetValueInteger($Ident) {
    $id = $this->GetIDForIdent($Ident);
    $val = GetValueInteger($id);
    return $val;
  }
  
  private function SetValueFloat($Ident, $value) {
    $id = $this->GetIDForIdent($Ident);
    if (GetValueFloat($id) <> $value) {
      SetValueFloat($id, $value);
      return true;
    }
    return false;
  }
  
  private function SetValueString($Ident, $value) {
    $id = $this->GetIDForIdent($Ident);
    if (GetValueString($id) <> $value) {
      SetValueString($id, $value);
      return true;
    }
    return false;
  }
  private function GetValueString($Ident) {
    $id = $this->GetIDForIdent($Ident);
    $val = GetValueString($id);
    return $val;
  }

  protected function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize) {
    if (!IPS_VariableProfileExists($Name)) {
    	IPS_CreateVariableProfile($Name, 1);
    } else {
    	$profile = IPS_GetVariableProfile($Name);
    	if ($profile['ProfileType'] != 1) {
    		throw new Exception("Variable profile type does not match for profile ".$Name);
    	}
    }	 
    		
    IPS_SetVariableProfileIcon($Name, $Icon);
    IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
    IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
	}
  
  protected function RegisterProfileIntegerAssociation($Name, $Icon, $Prefix, $Suffix, $Associations) {
    if ( sizeof($Associations) === 0 ){
      $MinValue = 0;
      $MaxValue = 0;
    } else {
      $MinValue = $Associations[0][0];
      $MaxValue = $Associations[sizeof($Associations)-1][0];
    }
        
    $this->RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, 0);
        
    foreach($Associations as $Association) {
      IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
    }
        
  }  
}
?>