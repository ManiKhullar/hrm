<body style='font-family: system-ui;'>
    <div class='table_wrapper'
        style='max-width: 95%; width: 1200px ; margin: 20px auto; border: 1px solid #777; border-radius: 8px; padding: 20px 20px 30px 20px;position: relative;background-color: #deeaf6;  box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px;'>
        <div class='table_top_wrapper' style='width: 100%; margin: auto; text-align: center;'>
            <h2>BLUETHINK IT CONSULTING PVT. LTD.</h2>
            <p style='font-weight: 600; font-size: 16px;'>B-12, SEC-2, Noida, U.P.-201301</p>
        </div>
        <div style='width: 100%; margin: auto;'>
            <div>
                <div style='width: 100%;background: #1266a9;color: #fff;'>
                    <div style='width: 100%;border: none; border-bottom: 3px solid #f3e6e6;padding: 12px 5px;'>Pay Slip
                        - <?php echo $salaryData['monthYear']; ?></div>
                </div>
                <div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        Employee Id </div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo $salaryData['employee_id']; ?></div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        Employee Name </div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo $salaryData['name']; ?></div>
                </div>
                <div style="clear: both;">
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        Employee Band</div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo $salaryData['employee_band']; ?></div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        Department </div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo $salaryData['department']; ?></div>
                </div>
                <div style="clear: both;">
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        Date of Joining</div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo date("d/m/Y",strtotime($salaryData['joining_date'])); ?></div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        Pan Number</div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo $salaryData['pan_number']; ?></div>
                </div>
                <div style="clear: both;">
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        Bank Name and Account number </div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo $salaryData['bank_name'] ."  ".$salaryData['acc_no']; ?></div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        Days worked in month </div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo $salaryData['day_worked_in_month']; ?></div>
                </div>
                <div style="clear: both;">
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600; '>
                        LWP current month </div>
                    <div
                        style='width: 25%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo $salaryData['LWPA']; ?></div>
                </div>
            </div>
            <div>
                <div
                    style='clear: both; width: 100%; display: flex !important; align-items: center;justify-content: space-around; background: #1266a9; border-right: 2px solid #000; color: #fff;  display: inline-block;'>
                    <div style='background: #1266a9; width: 49%;float: left; padding: 10px 5px; font-weight: 600;font-size: 20px;'>Earnings
                    </div>
                    <div style='background: #1266a9; width: 49%;float: left; padding: 10px 5px; font-weight: 600;font-size: 20px;'>Deductions
                    </div>
                </div>
                <div
                    style='clear: both; width: 100%;display: flex !important;justify-content: space-around; align-items: center; border: 1px solid #000; display: inline-block;'>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000;border-lft: 2px solid #000; '>
                        Basic & DA</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500;border-right: 2px solid #000; '>
                        <?php echo round($salaryData['basic_salary'],2); ?></div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        Provident Fund</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        N/A</div>
                </div>
                <div
                    style='clear: both; width: 100%;display: flex !important;justify-content: space-around; align-items: center; border: 1px solid #000; display: inline-block;'>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        HRA</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500;border-right: 2px solid #000; '>
                        <?php echo round($salaryData['house_rent_allowance'],2); ?></div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        E.S.I./Ensurance</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>N/A
                        </div>
                </div>
                <div
                    style='clear: both; width: 100%;display: flex !important;justify-content: space-around; align-items: center; border: 1px solid #000; display: inline-block;'>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        Conveyance</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500;border-right: 2px solid #000; '>
                        <?php if($salaryData['transport_allowance']): echo round($salaryData['transport_allowance'],2); else:?>N/A<?php endif;?></div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        Loan</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        N/A</div>
                </div>
                <div
                    style='clear: both; width: 100%;display: flex !important;justify-content: space-around; align-items: center; border: 1px solid #000; display: inline-block;'>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        Special Allowance</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500;border-right: 2px solid #000; '>
                        <?php echo round($salaryData['special_allowance'],2); ?></div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        TDS/IT</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php echo round($salaryData['tds'],2); ?></div>
                </div>

                <div
                    style='clear: both; width: 100%;display: flex !important;justify-content: space-around; align-items: center; border: 1px solid #000; display: inline-block;'>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        Other Allowance </div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500;border-right: 2px solid #000; '>
                        <?php if($salaryData['extra_pay']): echo round($salaryData['extra_pay'],2); else:?>N/A<?php endif;?></div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 600;border-right: 2px solid #000; '>
                        Total Deduction</div>
                    <div
                        style='width: 24%; float: left; padding:8px 5px; border: none; text-align: left; font-weight: 500; '>
                        <?php if($salaryData['tds']): echo round($salaryData['tds'],2); else:?>N/A<?php endif;?></div>
                </div>
                <div
                    style='clear: both; width: 100%;display: flex !important;justify-content: space-around; align-items: center; border: 2px solid #000;border-right: 1px solid #000;border-top: 1px solid #000; display: inline-block;'>
                    <div
                        style='width: 49%;float: left; text-align: right; padding: 10px 5px; font-weight: 600;font-size: 20px; '>
                        NET Salary</div>
                    <div
                        style='width: 49%;float: left; text-align: right; padding: 10px 5px; font-weight: 490;font-size: 20px;  '>
                        <?php echo round($salaryData['net_salary'],2); ?></div>
                </div>
                <div
                    style='clear: both; width: 100%;display: flex !important;justify-content: space-around; align-items: center; border: 2px solid #000;border-top: 0px;border-right: 1px solid #000; display: inline-block;'>
                    <div
                        style='width: 49%;float: left; text-align: left; padding: 10px 5px; font-weight: 600;font-size: 20px; '>
                        Amount in words:</div>
                    <div
                        style='width: 49%;float: left; text-align: right; padding: 10px 5px; font-weight: 500;font-size: 20px;  '>
                        <?php echo $salaryData['net_salary_in_words']; ?></div>
                </div>
                <div style='clear: both;'>
                    <div>'This payslip is system generated, no signature is required.'</div>
                </div>
            </div>
        </div>
    </div>
</body>
