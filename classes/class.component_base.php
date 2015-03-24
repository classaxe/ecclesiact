<?php
define("VERSION_COMPONENT_BASE", "1.0.22");
/*
Version History:
  1.0.22 (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static

*/

class Component_Base extends Component\Base
{
    // Deprecated method names for backward compatability
    protected function _draw_control_panel($extra_break = false)
    {
        $this->drawControlPanel($extra_break);
    }

    protected function _draw_section_container_close()
    {
        $this->drawSectionContainerClose();
    }

    protected function _draw_section_container_open()
    {
        $this->drawSectionContainerOpen();
    }

    protected function _draw_status()
    {
        $this->drawStatus();
    }

    public function get_parameter($parameters, $key, $default = false)
    {
        if ($parameters==="") {
            return $default;
        }
        $arr = explode(OPTION_SEPARATOR, $parameters);
        for ($i=0; $i<count($arr); $i++) {
            $row = trim($arr[$i]);
            if ($row==="?") {
                return $row;
            }
            $pair = explode("=", $row);
            if (strToUpper($key)===strToUpper($pair[0])) {
                array_shift($pair);
                $result = implode("=", $pair);
                if ($default && $result=='') {
                    return $default;
                }
                return $result;
            }
        }
        return $default;
    }

    public static function get_parameter_defaults_and_values(
        $ident,
        $instance,
        $force_values,
        $parameter_spec,
        $presets = array()
    ) {
        return static::getParameterDefaultsAndValues(
            $ident,
            $instance,
            $force_values,
            $parameter_spec,
            $presets
        );
    }

    public static function get_parameter_for_instance($instance, $parameter_csv, $key, $default = false)
    {
        return static::getParameterForInstance($instance, $parameter_csv, $key, $default);
    }

    public static function get_parameters($page = false, $systemID = SYS_ID)
    {
        return static::getParameters($page = false, $systemID = SYS_ID);
    }

    protected function _setup($instance, $args, $disable_params)
    {
        $this->setup($instance, $args, $disable_params);
    }

    protected function _setup_load_block_layout($blockLayoutName)
    {
        return $this->setupLoadBlockLayout($blockLayoutName);
    }

    protected function _setup_load_parameters()
    {
        $this->setupLoadParameters();
    }

    protected function _setup_load_user_groups()
    {
        $this->setupLoadUserGroups();
    }

    protected function _setup_load_user_rights()
    {
        $this->setupLoadUserRights();
    }

    public static function get_help($ident, $instance, $force_values, $parameter_spec, $cp_defaults)
    {
        return static::getHelp($ident, $instance, $force_values, $parameter_spec, $cp_defaults);
    }

    public static function get_safe_ID($ident, $instance = '')
    {
        return static::getSafeID($ident, $instance);
    }

    public static function getVersion()
    {
        return VERSION_COMPONENT_BASE;
    }
}
